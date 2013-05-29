# Authority

A simple and flexible activity/resource based authorization system for PHP

[![Build Status](https://travis-ci.org/machuga/authority.png?branch=develop)](https://travis-ci.org/machuga/authority)


## Installation via Composer

Add Authority to your composer.json file to require Authority

```
"require" : {
    "machuga/authority" : "dev-master"
} 
```

And install via composer

`composer install`

Further installation information is available in `docs/install.md`

## Introduction

Authority is an authorization system for PHP that focuses more on the concept of activities and resources rather than roles.  Using different user roles is still completely possible and often needed, but rather than determining functionality based on roles throughout your app, Authority allows you to simply check if a user is allowed to perform an action on a given resource or activity.

Let's take an example of editing a Post `$post`.

First we'll use standard role-based authorization checks for roles that may be able to delete a post

```php
if ($user->hasRole('admin') || $user->hasRole('moderator') || $user->hasRole('editor')) {
    // Can perform actions on resource
    $post->delete();   
}
```
While this certainly works, it is highly prone to needing changes, and could get quite large as roles increase.

Let's instead see how simply checking against an activity on a resourse looks.

```php
if ($authority->can('edit', $post)) {
    // Can perform actions on resource
    $post->delete();
}
```

Instead of littering the codebase with several conditionals about user roles, we only need
to write out a conditional that reads like "if the current user can edit this post". 

## Default behavior

Two important default behaviors of Authority to keep in mind

1. **Unspecified rules are denied** - if you check a rule that has not been set, Authority will deny the activity.
2. **Rules are evaluated in order of declaration** - last rule takes precedence.

## Basic usage

Authority is intented to be instantiated once per application (though supports multiple instances).  It works well with an IoC (Inversion of Control) container that supports singleton access, like [Laravel's IoC](https://github.com/illuminate/container), or by using standard dependency injection.  You may assign rules prior to your app authorizing resources, or add at any time.  

The Authority constructor requires at least one argument - the object that represents the current user.  We'll cover the second optional argument later.

```php    
<?php

use Authority\Authority;

// Assuming you have your current user stored
// in $currentUser, with the id property of 1
$authority = new Authority($currentUser);

/*
    * Let's assign an alias to represent a group of actions
    * so that we don't have to handle each action individually each time
    */
$authority->addAlias('manage', array('create', 'update', 'index', 'read', 'delete'));

// Let's allow a User to see all other User resources
$authority->allow('read', 'User');

/*
    * Now let's restrict a User to managing only hiself or herself through
    * the use of a conditional callback.
    *
    * Callback Parameters:
    * $self is always the current instance of Authority so that we always
    * have access to the user or other functions within the scope of the callback.
    * $user here will represent the User object we'll pass into the can() method later
    */
$authority->allow('manage', 'User', function($self, $user) {
    // Here we'll compare id's of the user objects - if they match, permission will
    // be granted, else it will be denied.
    return $self->user()->id === $user->id;
});

// Now we can check to see if our rules are configured properly

$otherUser = (object) array('id' => 2);
if ($authority->can('read', 'User')) {
    echo 'I can read about any user based on class!';
}

if ($authority->can('read', $otherUser)) {
    echo 'I can read about another user!';
}

if ($authority->can('delete', $otherUser)) {
    echo 'I cannot edit this user so you will not see me :(';
}

if ($authority->can('delete', $user)) {
    echo 'I can delete my own user, so you see me :)';
}
```

If we run the above script, we will see:

    I can read about any user based on class!
    I can read about another user!
    I can delete my own user, so you see me :)
    

## Intermediate Usage

Coming soon

## Advanced Usage

Coming soon
