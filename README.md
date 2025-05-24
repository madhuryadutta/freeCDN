# 🚀 IndieCDN — Free CDN for Open Source Projects

[![License: MIT](https://img.shields.io/badge/License-MIT-yellow.svg)](LICENSE)
[![CDN Status](https://img.shields.io/badge/CDN-Free-blueviolet?style=flat-square&logo=cloudflare)](#)

**IndieCDN** is a free CDN built for developers, educators, and the open-source community. It enables fast, global delivery of public assets — Git-hosted files, website images, and static resources — through a simple and developer-friendly API.

---

## ✨ Features

- 📦 **Git File CDN** — Serve files directly from GitHub, GitLab, and Bitbucket repositories.
- 🌐 **Remote File Proxy** — Proxy and cache any public static file from any domain.
- 🖼️ **Image Processor (coming soon)** — Resize, optimize, and convert images on the fly.
- ⚡ **Edge Optimized (coming soon)** — Delivered globally from multiple edge locations close to your users.
- 🛠️ **Simple RESTful URLs** — No authentication or keys required.

---

## 📁 Git File CDN

Use this pattern to fetch public files from Git repositories:

```bash
/{service}/{user}/{repo}/{tag}/{file}
````

Where:

* `service` is one of: `gh` (GitHub), `gl` (GitLab), or `bb` (Bitbucket)
* `user` is the Git username or organization
* `repo` is the repository name
* `tag` is a branch, tag, or commit hash
* `file` is the path to the file in the repo

**Example:**

```
/gl/a_rost/procedural-city/master/ambient_occlusion.py
```

---

## 🌐 Remote File CDN

Proxy any public file hosted on another domain:

```bash
/img/{domain}/{file-path}
```

**Examples:**

```
/img/example.org/assets/js/main.js
/img/cdn.somecdn.com/library/font.woff2
```

**Rules:**

* Do **not** include `http://` or `https://`
* Do **not** use a trailing slash
* Only public, static files are supported

---

## 🖼️ Image Processor (Coming Soon)

We are working on a full image processing API with support for:

* Resizing
* Format conversion (`webp`, `jpeg`, etc.)
* Optimization
* Blur, grayscale, crop, and more

---

## ⚙️ Tech Stack

* **Backend**: Laravel 10+
* **Caching**: Smart file caching & fallback handling
* **Security**: Sanitized input, CORS headers, and MIME enforcement

---

## 🌍 Edge POP Locations

To deliver files with low latency, IndieCDN uses strategically placed reverse proxies in:

* Mumbai 🇮🇳
* Singapore 🇸🇬

---


## 💡 About

IndieCDN is a side project maintained by passionate developers from India, supporting the global open-source movement.
We believe in fast, free access to code and content.

> Made in 🇮🇳 India. Built for the 🌐 world.
