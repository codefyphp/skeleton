<h1 align="center">
    <a href="https://codefyphp.com/" target="_blank"><img src="https://downloads.joshuaparker.blog/images/codefyphp-460x105.png" width="400" alt="CodefyPHP Logo"></a>
</h1>

<p align="center">
    <a href="https://codefyphp.com/"><img src="https://img.shields.io/packagist/v/CodefyPHP/codefy?label=CodefyPHP" alt="Latest Stable Version"></a>
    <a href="https://www.php.net/"><img src="https://img.shields.io/badge/PHP-8.2-777BB4.svg?style=flat&logo=php" alt="PHP 8.2"/></a>
    <a href="https://packagist.org/packages/codefyphp/codefy"><img src="https://img.shields.io/packagist/l/codefyphp/codefy" alt="License"></a>
    <a href="https://packagist.org/packages/codefyphp/codefy"><img src="https://img.shields.io/packagist/dt/codefyphp/codefy" alt="Total Downloads"></a>
    <a href="https://codefyphp.com/community/"><img src="https://img.shields.io/badge/Forum-AE508D.svg?label=Support&style=flat" alt="CodefyPHP Support Forum"></a>
</p>

---
> This repository contains the skeleton package for creating a new CodefyPHP project. If you are only interested in the 
> core framework, visit the main [CodefyPHP Framework repository](https://github.com/codefyphp/codefy).

CodefyPHP is __not__ a framework such as the likes of Symfony, Laravel, Codeigniter or CakePHP. Codefy is a simple,
light framework providing interfaces and implementations for architecting a Domain Driven project with
CQRS, Event Sourcing and implementations of [PSR-3](https://www.php-fig.org/psr/psr-3),
[PSR-6](https://www.php-fig.org/psr/psr-6), [PSR-7](https://www.php-fig.org/psr/psr-7),
[PSR-11](https://www.php-fig.org/psr/psr-11), [PSR-12](https://www.php-fig.org/psr/psr-12/),
[PSR-15](https://www.php-fig.org/psr/psr-15), [PSR-16](https://www.php-fig.org/psr/psr-16)
and [PSR-17](https://www.php-fig.org/psr/psr-17).

The philosophy of Codefy is that code should be systematized, maintainable, and follow OOP (Object-Oriented Programming).
CodefyPHP tries not to be too opinionated, yet encourages best practices and coding standards by following [Qubus Coding
Standards](https://github.com/QubusPHP/qubus-coding-standard). Use Codefy as you see fit. You can tap into all, some or
none of the features and instead use the interfaces to build your own implementations for a domain driven project.

<h3 align="center">
    <img src="https://downloads.joshuaparker.blog/images/UserId.png" width="660" alt="UserId ValueObject">
</h3>

## 📍 Requirement
- PHP 8.2+
- Additional constraints based on which components are used.

## 🏆 Highlighted Features
- A powerful [routing engine](https://docs.qubusphp.com/routing/)
- Robust [dependency injector](https://docs.qubusphp.com/dependency-injector/) for bootstrapping
- Adapters for cookies, sessions and cache storage
- Provides a simple hook and event system without affecting core code
- Encourages object-oriented programming
- Multiple PSR implementations
- Dual query builders with migrations
- Scheduler for scheduling tasks/jobs
- Security and sanitizing helpers
- Dual templating engines
- NIST Level 2 Standard Role-Based Access Control

## 📦 Installation

To create a new project, run the following command:

```bash
composer create-project codefyphp/skeleton my-app-name
```

Replace `my-app-name` with the directory name to which you are starting your new project. Once installed, `cd` into 
the root directory of your app and start the development server by running the following terminal command:

```bash
php codex serve
```

Once the server starts, visit the new install at `http://localhost:8080`.

## 🕑 Releases

| Version | Minimum PHP Version | Release Date   | Bug Fixes Until | Security Fixes Until |
|---------|---------------------|----------------|-----------------|----------------------|
| 1       | 8.2                 | September 2023 | July 2024       | March 2025           |
| 2 - LTS | 8.2                 | September 2024 | September 2027  | September 2028       |
| 3       | 8.3                 | October 2024   | August 2025     | April 2026           |
| 4       | 8.4                 | February 2025  | December 2025   | August 2026          |
| 5 - LTS | 8.4                 | April 2025     | April 2028      | April 2029           |

## 📘 Documentation

Documentation is still a work in progress. Between the [Qubus Components](https://docs.qubusphp.com/) documentation
and [CodefyPHP's](https://codefyphp.com/documentation/) documentation, that should help you get started. If you have questions or
need help, feel free to ask for help in the [forums](https://codefyphp.com/community/).

## 🙌 Sponsors

If you use CodefyPHP or you are interested in supporting the continued development of my opensource projects,
please consider sponsoring me via [Github](https://github.com/sponsors/nomadicjosh).

## 🖋 Contributing

CodefyPHP could always be better! If you are interested in contributing enhancements or bug fixes, here are a few
rules to follow in order to ease code reviews, and discussions before I accept and merge your work.
- You MUST follow the [QubusPHP Coding Standards](https://github.com/QubusPHP/qubus-coding-standard).
- You MUST write (or update) unit tests.
- You SHOULD write documentation.
- Please, write [commit messages that make sense](http://tbaggery.com/2008/04/19/a-note-about-git-commit-messages.html),
  and rebase your branch before submitting your Pull Request.
- Please [squash your commits](http://gitready.com/advanced/2009/02/10/squashing-commits-with-rebase.html) too.
  This is used to "clean" your Pull Request before merging it (I don't want commits such as `fix tests`, `fix 2`, `fix 3`,
  etc.).

## 🔐 Security Vulnerabilities

If you discover a vulnerability in the code, please email [joshua@joshuaparker.dev](mailto:joshua@joshuaparker.dev).

## 📄 License

CodefyPHP is opensource software licensed under the [MIT License](https://opensource.org/license/MIT/).