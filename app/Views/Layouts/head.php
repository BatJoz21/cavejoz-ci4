<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-header" content="<?= csrf_header() ?>">
    <meta name="csrf-token" content="<?= csrf_hash() ?>">
    <title><?= $this->renderSection('title') ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="<?= base_url('styles/app.css') ?>">
    <link rel="stylesheet" href="<?= base_url('styles/header.css') ?>">
    <link rel="stylesheet" href="<?= base_url('styles/auth.css') ?>">
    <link rel="stylesheet" href="<?= base_url('styles/search.css') ?>">
    <link rel="stylesheet" href="<?= base_url('styles/profile.css') ?>">
    <link rel="stylesheet" href="<?= base_url('styles/friendship.css') ?>">
    <link rel="stylesheet" href="<?= base_url('styles/post.css') ?>">
</head>