# Postaverse Project Structure

This document outlines the organization of the Postaverse project codebase, explaining the reasoning behind the file structure and how various components are organized.

## Directory Structure Overview

```
app/
├── Actions/                 # Jetstream and Fortify actions
├── Http/
│   ├── Controllers/         # Controllers
│   │   └── Api/             # API Controllers (versioned)
│   └── Middleware/          # Middleware classes
├── Livewire/               # Livewire components organized by feature
│   ├── Admin/              # Admin dashboard components
│   ├── Blog/               # Blog-related components
│   ├── Post/               # Post-related components
│   ├── User/               # User profile components
│   └── Interaction/        # Social interaction components
├── Models/                 # Eloquent models organized by domain
│   ├── Admin/              # Admin-related models
│   ├── Blog/               # Blog-related models
│   ├── Post/               # Post-related models
│   ├── User/               # User-related models
│   └── Interaction/        # Interaction-related models
├── Services/               # Service classes for business logic
└── View/                   # View related components
```

## Livewire Components Organization

Livewire components are organized by feature domain rather than technical function. This makes it easier to locate related code:

- **Admin/**: Components for admin dashboard, user management, and system configuration
- **Blog/**: Components for blog creation, listing, and interaction
- **Post/**: Components for post creation, feed, and interactions
- **User/**: Components for user profiles, settings, and account management
- **Interaction/**: Components for social interactions (follows, notifications, search)

## Model Organization

Models are organized by domain to improve discoverability:

- **Admin/**: Admin-specific models like AdminLogs, Whitelisted
- **Blog/**: Blog-related models like Blog, BlogComment, BlogLike, BlogImage
- **Post/**: Post-related models like Post, Comment, Like, PostImage
- **User/**: User-related models like User, BlockedUser, Banned, Site
- **Interaction/**: Social interaction models like Follower, Notification

## View Organization

Views are organized to match the Livewire component structure:

```
resources/
└── views/
    └── livewire/
        ├── Admin/           # Admin-related views
        ├── Blog/            # Blog-related views
        ├── Post/            # Post-related views
        ├── User/            # User-related views
        └── Interaction/     # Interaction-related views
```

## Services

The `app/Services` directory contains business logic classes that are used across multiple components:

- **Profanity.php**: Service for checking and filtering profanity in user content

## Future Development Guidelines

1. **Adding New Features**: 
   - Place Livewire components in the appropriate feature directory
   - Place models in the corresponding domain directory
   - Place views in the corresponding directory under resources/views/livewire/

2. **Naming Conventions**:
   - Component classes: `FeatureName.php`
   - View files: `feature-name.blade.php` (kebab-case)
   - Model classes: `ModelName.php` (singular)

3. **Relationships**:
   - Import models from their new namespaces (e.g., `use App\Models\User\User;`)
   - Use typed return values for relationships (e.g., `public function user(): BelongsTo`)
