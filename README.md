# Postaverse
![loc](https://img.shields.io/endpoint?url=https://ghloc.vercel.app/api/postaverse/postaverse/badge?filter=.php$&label=Lines%20of%20Code%20(PHP)&color=B66BFE)

Welcome to **Postaverse** - a friendly social space where people connect and share what matters to them. Our open-source platform is built to help communities thrive online!

## What Can You Do on Postaverse?

- 👤 Create your own personalized profile
- 📝 Share posts with text and images
- 📄 Create and publish blogs
- 💬 Comment on posts and blogs
- ❤️ Like content you enjoy
- 👥 Follow others to see their updates
- 🔔 Get notified when something happens
- 🔍 Find friends and interesting content

## Getting Started

Setting up Postaverse on your computer is simple:

1. **Download the project**
    ```bash
    git clone https://github.com/Postaverse/postaverse.git
    cd postaverse
    ```

2. **Install what's needed**
    ```bash
    composer install
    npm install
    npm run dev
    ```

3. **Set up your environment**
    - Copy `.env.example` to `.env`
    - Update it with your information

4. **Final setup steps**
    ```bash
    php artisan key:generate
    php artisan migrate
    php artisan serve
    ```

Your Postaverse will be ready at `http://localhost:8000`!

## Content System Architecture

Postaverse uses a unified content handling system that combines blog and post functionality:

- **Content Components**: All content (posts and blogs) is handled by the same set of components in the `App\Livewire\Content` namespace
- **Type-Based Behavior**: Components use a `type` parameter ('post' or 'blog') to determine behavior and rendering
- **Consistent UI**: Unified templates maintain a consistent user experience across different content types
- **Code Efficiency**: Shared code reduces duplication while maintaining distinct content type features

## Join Our Community

We'd love your help making Postaverse better! You can:
- Share ideas for new features
- Help fix bugs
- Improve the design
- Spread the word about Postaverse

To contribute, just fork the project, make your changes, and send us a pull request!

## Thank You

This project wouldn't be possible without:
- Our amazing contributors
- The Laravel community
- Everyone who uses and supports Postaverse

## License

Postaverse is available under the MPL 2.0 License - see the [LICENSE](LICENSE.md) file.
