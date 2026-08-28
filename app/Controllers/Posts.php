<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Service\PostApiService;
use App\Service\UserApiService;
use CodeIgniter\Exceptions\PageNotFoundException;
use GuzzleHttp\Exception\ConnectException;
use GuzzleHttp\Exception\RequestException;
use GuzzleHttp\Exception\ResponseException;

class Posts extends BaseController
{
    private PostApiService $api;
    private UserApiService $uApi;
    private Likes $like;
    private Comments $comment;

    public function __construct()
    {
        $this->api = new PostApiService();
        $this->uApi = new UserApiService();
        $this->like = new Likes();
        $this->comment = new Comments();
    }

    public function new()
    {
        // Get current path for toggle add post button visibility
        $currentPath = uri_string();

        return view('Posts/create', ['currentPath' => $currentPath]);
    }

    public function create()
    {
        // User Input Validation
        $rules = config('Validation')->newPost;
        if(!$this->validate($rules)) {
            return redirect()->back()
                             ->with('errors', $this->validator->getErrors())
                             ->withInput();
        }

        // Get uploaded file
        $content = $this->request->getFile('content');

        // Call API and handle it's response
        $response = $this->api->createNewPost([
            'visibility'    => $this->request->getPost('visibility'),
            'caption'       => $this->request->getPost('caption')
        ], $content);
        if(!$response['success']) {
            return redirect()->back()
                             ->with('error', 'Upload failed!')
                             ->withInput();
        }

        return redirect()->to('/profile')
                         ->with('message', $response['data']['message']);
    }

    public function feeds()
    {
        // Get current page
        $page = $this->request->getGet('page') ?? '1';

        // Call the API method
        $response = $this->api->getFeeds($page);
        if(!$response['success']) {
            throw PageNotFoundException::forPageNotFound("Unable to fetch data for your feeds");
        }
        
        // Get post's like and comments data
        $posts = $response['data'] ?? [];
        foreach($posts as &$post) {
            $post['liked_by_me'] = $this->like->isUserLikedThisPost($post['id']);
            $post['like_count'] = $this->like->getTotalLikesOfThisPost($post['id']);
            $post['comment_count'] = $this->comment->getTotalCommentOfPost($post['id']);
        }
        unset($post);

        $response2 = $this->uApi->getUserAvatarFileName();
        $avatarUrl = 'default';
        if($response2['success']) {
            $avatarUrl = $response2['data']['avatar_url'];
        }

        return view('Home/index', [
            'posts'         => $posts,
            'avatarUrl'     => $avatarUrl
        ]);
    }

    public function view(int $postID)
    {
        // Get requested page number
        $currentPage = $this->request->getGet('page') ?? '1';

        // Call the API to get user's posts
        $responsePost = $this->api->getPostByID($postID);
        if(!$responsePost['success']) {
            return redirect()->to('/')
                             ->with('error', 'An error has occured');
        }

        // Get post's like and comments data
        $post = $responsePost['data'] ?? [];
        $postComments = [];
        if(!empty($post)) {
            $post['liked_by_me'] = $this->like->isUserLikedThisPost($post['id']);
            $post['like_count'] = $this->like->getTotalLikesOfThisPost($post['id']);
            $post['comment_count'] = $this->comment->getTotalCommentOfPost($post['id']);

            $postComments = $this->comment->getAllCommentsOfAPost($post['id'], $currentPage);
        }

        $totalPage = ceil($postComments['total'] / 10);

        return view('Comments/index', [
            'post'          => $post,
            'comments'      => $postComments['comments'],
            'currentPage'   => (int) $currentPage,
            'totalPage'     => $totalPage
        ]);
    }

    public function getPostContentImage(string $filename)
    {
        try {
            // Call the API to get the content image
            $response = $this->api->getPostContentImage($filename);
            
            // Get the response's body
            $body = (string) $response->getBody();

            // Verify the image type
            $info = @getimagesizefromstring($body);
            if($info === false || !isset(self::ALLOWED_IMAGE_TYPES[$info[2]])) {
                log_message('warning', 'Non image content served from image route', [
                    'file'      => $filename,
                    'api_type'  => $response->getHeaderLine('Content-Type'),
                ]);

                return $this->response->setStatusCode(415)->setBody('');
            }

            // Set up to show the image
            $mime = self::ALLOWED_IMAGE_TYPES[$info[2]];
            return $this->response->setStatusCode(200)
                                  ->setHeader('Content-Type', $mime)
                                  ->setHeader('Content-Length', strlen($body))
                                  ->setHeader('X-Content-Type-Option', 'nosniff')
                                  ->setHeader('Content-Security-Policy', "default-src 'none'; sandbox")
                                  ->setBody($body);
        } catch(ConnectException $e) {
            // API unreachable
            log_message('error', 'Failed to connect to the API', [
                'file'      => $filename,
                'message'   => $e->getMessage(),
            ]);

            return $this->response->setStatusCode(503)->setBody('');
        } catch(ResponseException $e) {
            // API responded with an error status code
            $statusCode = $e->getResponse()?->getStatusCode() ?? 502;

            log_message('error', 'Image API error', [
                'status'    => $statusCode,
                'file'      => $filename,
            ]);

            return $this->response->setStatusCode($statusCode == 404 ? 404 : 502)->setBody('');
        } catch(RequestException $e) {
            // API request failed before any response
            log_message('error', 'Image request failed before response', [
                'file'      => $filename,
                'message'   => $e->getMessage(),
            ]);

            return $this->response->setStatusCode(502)->setBody('');
        } catch(\Throwable $e) {
            log_message('critical', 'Unexpected image proxy failure', [
                'message'   => $e->getMessage(),
            ]);

            return $this->response->setStatusCode(500)->setBody('');
        }
    }

    public function getTotalPostOfUser(int $uID)
    {
        $response = $this->api->getTotalPostByUId($uID);
        if(!$response['success']) {
            return 0;
        }

        return $response['data'];
    }

    public function loadPostsDataForPostCard(int $id)
    {
        // Call the API to get user's posts
        $responsePost = $this->api->getUserPosts($id);
        if(!$responsePost['success']) {
            return redirect()->to('/')
                             ->with('error', 'Failed to load post data');
        }

        // Get post's like and comments data
        $posts = $responsePost['data'] ?? [];
        foreach($posts as &$post) {
            $post['liked_by_me'] = $this->like->isUserLikedThisPost($post['id']);
            $post['like_count'] = $this->like->getTotalLikesOfThisPost($post['id']);
            $post['comment_count'] = $this->comment->getTotalCommentOfPost($post['id']);
        }
        unset($post);

        return $posts;
    }

    public function edit(int $postID)
    {
        // Call the API and handle it's response
        $response = $this->api->getPostByID($postID);
        if(!$response['success']) {
            return redirect()->back()
                             ->with('error', 'An error has occured')
                             ->withInput();
        }

        return view('Posts/edit', ['post' => $response['data']]);
    }

    public function update(int $postID)
    {
        // Validate user's input
        $rules = config('Validation')->editPost;
        if(!$this->validate($rules)) {
            return redirect()->back()
                             ->with('errors', $this->validator->getErrors())
                             ->withInput();
        }

        // Get uploaded file
        $content = $this->request->getFile('content');

        // Call the API and handle it's response
        $response = $this->api->updatePost([
            'id'            => $postID,
            'visibility'    => $this->request->getPost('visibility'),
            'caption'       => $this->request->getPost('caption')
        ], $content);
        if(!$response['success']) {
            return redirect()->back()
                             ->with('error', 'Failed to edit this post')
                             ->withInput();
        }

        return redirect()->to('/profile')
                         ->with('message', $response['data']['message']);
    }

    public function delete(int $postID)
    {
        // Call the API and handle it's response
        $response = $this->api->deletePost($postID);
        if(!$response['success']) {
            return redirect()->back()
                             ->with('error', 'Failed to delete this post')
                             ->withInput();
        }

        return redirect()->to('/profile')
                         ->with('message', $response['data']['message']);
    }

    public function loadMorePostsForFeed(int $page)
    {
        // Call the API method
        $response = $this->api->getFeeds($page);
        if(!$response['success']) {
            return $this->response->setStatusCode(500)->setJSON([
                'success'   => false,
                'message'   => 'failed to fetch more post'
            ]);
        }
        
        // Get post's like and comments data
        $posts = $response['data'] ?? [];
        foreach($posts as &$post) {
            $post['liked_by_me'] = $this->like->isUserLikedThisPost($post['id']);
            $post['like_count'] = $this->like->getTotalLikesOfThisPost($post['id']);
            $post['comment_count'] = $this->comment->getTotalCommentOfPost($post['id']);
        }
        unset($post);

        // return data as JSON
        return $this->response->setJSON([
            'success'   => true,
            'posts'     => $posts
        ]);
    }

    public function loadMorePosts(int $id, int $page)
    {
        // Call the API
        $response = $this->api->getUserPosts($id, $page);
        if(!$response['success']) {
            return $this->response->setStatusCode(500)->setJSON([
                'success'   => false,
                'message'   => 'failed to load more posts'
            ]);
        }

        // Get post's like data
        $posts = $responsePost['data'] ?? [];
        foreach($posts as &$post) {
            $post['liked_by_me'] = $this->like->isUserLikedThisPost($post['id']);
            $post['like_count'] = $this->like->getTotalLikesOfThisPost($post['id']);
            $post['comment_count'] = $this->comment->getTotalCommentOfPost($post['id']);
        }
        unset($post);

        // return data as JSON
        return $this->response->setJSON([
            'success'   => true,
            'posts'     => $posts
        ]);
    }
}
