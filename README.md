# Postaverse

> [!NOTE]
> Postaverse is not ready to be a social media platform yet. We require contributions to make this possible!

Welcome to the **Postaverse** - an open-source project designed to create a robust and scalable social media network. This platform allows users to connect, share, and communicate with each other seamlessly.

## Table of Contents

- [Features](#features)
- [Installation](#installation)
- [Usage](#usage)
- [Contributing](#contributing)
- [License](#license)
- [Acknowledgements](#acknowledgements)

## Features

- **User Authentication**: Secure sign-up and login functionality powered by Laravel Jetstream.
- **User Profiles**: Customizable user profiles with avatars and bios.
- **Posts**: Create, edit, and delete posts with text and images.
- **Comments**: Comment on posts and interact with other users.
- **Likes**: Like posts and comments.
- **Followers**: Follow other users to see their updates.
- **Notifications**: Get notified about interactions and updates.
- **Search**: Search for users, posts, and hashtags.
- **Responsive Design**: Fully responsive for mobile and desktop users.
- **Privacy Settings**: Control the visibility of your posts and profile.

## Installation

To set up the project locally, follow these steps:

1. **Clone the repository**:
    ```bash
    git clone https://github.com/Postaverse/postaverse-v2.git
    cd postaverse-v2
    ```

2. **Install dependencies**:
    ```bash
    composer install
    npm install
    npm run dev
    ```

3. **Set up environment variables**:
    Copy the `.env.example` file to `.env` and update the following variables:
    ```
    APP_NAME=Postaverse
    APP_URL=http://localhost
    DB_CONNECTION=mysql
    DB_HOST=127.0.0.1
    DB_PORT=3306
    DB_DATABASE=your_database_name
    DB_USERNAME=your_database_username
    DB_PASSWORD=your_database_password
    ```

4. **Generate application key**:
    ```bash
    php artisan key:generate
    ```

5. **Run database migrations**:
    ```bash
    php artisan migrate
    ```

6. **Seed the database** (optional):
    ```bash
    php artisan db:seed
    ```

7. **Start the development server**:
    ```bash
    php artisan serve
    ```

Your application should now be running on `http://localhost:8000`.

## Usage

Once the platform is set up, you can:

- **Sign Up**: Create a new account.
- **Log In**: Access your account using your credentials.
- **Explore**: Browse posts, follow users, and interact with content.
- **Profile Management**: Update your profile information and privacy settings.

## Contributing

We welcome contributions from the community! To contribute:

1. **Fork the repository**.
2. **Create a new branch**:
    ```bash
    git checkout -b feature/your-feature-name
    ```
3. **Make your changes and commit them**:
    ```bash
    git commit -m "Add your message here"
    ```
4. **Push to your branch**:
    ```bash
    git push origin feature/your-feature-name
    ```
5. **Create a pull request**.

Please ensure your code follows our coding standards and includes tests where applicable.

## License

This project is licensed under the MIT License - see the [LICENSE](LICENSE) file for details.

## Acknowledgements

- [Laravel](https://laravel.com/)
- [Laravel Jetstream](https://jetstream.laravel.com/)
- [Tailwind CSS](https://tailwindcss.com/)
- [Livewire](https://laravel-livewire.com/)
- [Alpine.js](https://alpinejs.dev/)

Thank you to all our contributors and the open-source community!