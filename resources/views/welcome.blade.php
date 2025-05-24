<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>IndieCDN - Free CDN for Open Source</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
</head>

<body>

    <div class="container mt-4">
        <h1 class="text-center">🚀 IndieCDN - Free CDN for Open Source</h1>
        <p class="text-center">Quickly serve files from GitHub, GitLab, and Bitbucket.</p>

        <!-- Tabs Navigation -->
        <ul class="nav nav-tabs" id="cdnTabs" role="tablist">
            <li class="nav-item" role="presentation">
                <button class="nav-link active" id="git-tab" data-bs-toggle="tab" data-bs-target="#git-downloader"
                    type="button" role="tab">📁 Git Downloader</button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="cdn-tab" data-bs-toggle="tab" data-bs-target="#cdn-feature"
                    type="button" role="tab">🌐 Simple CDN</button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="image-tab" data-bs-toggle="tab" data-bs-target="#image-processor"
                    type="button" role="tab">🖼️ Image Processor</button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="about-tab" data-bs-toggle="tab" data-bs-target="#about" type="button"
                    role="tab">ℹ️ About IndieCDN</button>
            </li>
        </ul>

        <!-- Tabs Content -->
        <div class="tab-content mt-3" id="cdnTabsContent">

            <!-- Git Downloader Tab -->
            <div class="tab-pane fade show active" id="git-downloader" role="tabpanel">
                <h3>📁 Git Downloader</h3>
                <p>Fetch files from GitHub, GitLab, or Bitbucket using IndieCDN.</p>

                <h4>🔗 Example URLs:</h4>
                <ul>
                    <li>
                        Fetching a file from GitLab:
                        <pre><code>{{ config('app.url') }}/gl/a_rost/procedural-city/master/ambient_occlusion.py</code></pre>
                    </li>
                    <li>
                        Fetching another file:
                        <pre><code>{{ config('app.url') }}/gl/a_rost/procedural-city/master/old/navigation.c</code></pre>
                    </li>
                </ul>

                <h5>🎯 Try It Now:</h5>
                <div class="mb-3">
                    <label for="service">Select Service:</label>
                    <select id="service" class="form-control">
                        <option value="gh">GitHub</option>
                        <option value="gl">GitLab</option>
                        <option value="bb">Bitbucket</option>
                    </select>
                </div>
                <input type="text" id="user" class="form-control mb-2" placeholder="Username (e.g., mrdoob)">
                <input type="text" id="repo" class="form-control mb-2"
                    placeholder="Repository (e.g., three.js)">
                <input type="text" id="tag" class="form-control mb-2" placeholder="Branch/Tag (e.g., master)">
                <input type="text" id="file" class="form-control mb-2"
                    placeholder="File path (e.g., build/three.min.js)">
                <button class="btn btn-primary" onclick="generateURL()">Generate IndieCDN URL</button>

                <h5 class="mt-3">Generated URL:</h5>
                <pre id="generated-url">Enter details above</pre>
            </div>

            <!-- Image Processor Tab -->
            <div class="tab-pane fade" id="image-processor" role="tabpanel">
                <h3>🖼️ Image Processor</h3>
                <p>Resize, optimize, and convert images using IndieCDN.</p>
                <p>Feature Coming Soon!</p>
            </div>

            <!-- About IndieCDN Tab -->
            <div class="tab-pane fade" id="about" role="tabpanel">
                <h3>ℹ️ About IndieCDN</h3>
                <p>IndieCDN is a free content delivery network that serves open-source files quickly.</p>
                <h4>How It Works:</h4>
                <ul>
                    <li>GitHub: <code>/gh/:user/:repo/:tag/:file</code></li>
                    <li>GitLab: <code>/gl/:user/:repo/:tag/:file</code></li>
                    <li>Bitbucket: <code>/bb/:user/:repo/:tag/:file</code></li>
                </ul>
                <p>🚀 Start using IndieCDN today!</p>
            </div>

        </div>
        <!-- Add this inside the Tabs Content -->
        <div class="tab-pane fade" id="cdn-feature" role="tabpanel">
            <h3>🌐 Simple CDN for Any Domain</h3>
            <p>Use IndieCDN to deliver any static image from any public domain — fast and cache-optimized!</p>

            <h4>How It Works:</h4>
            <p>You can serve files from any domain using a simple route:</p>
            <pre><code>{{ config('app.url') }}/img/{domain}/{file}</code></pre>
            <p><strong>Example:</strong></p>
            <pre><code>{{ config('app.url') }}/img/example.com/images/logo.png</code></pre>

            <h5>🧪 Try It Yourself:</h5>
            <div class="mb-3">
                <input type="text" id="cdn-domain" class="form-control mb-2"
                    placeholder="Domain (e.g., example.com)">
                <input type="text" id="cdn-path" class="form-control mb-2"
                    placeholder="File Path (e.g., images/logo.png)">
                <button class="btn btn-success" onclick="generateCDNUrl()">Generate CDN URL</button>
            </div>

            <h5 class="mt-3">Generated CDN URL:</h5>
            {{-- <pre id="cdn-url">Enter details above</pre> --}}
            <div id="cdn-url-container" class="alert alert-light border border-primary p-3 mt-3 text-break"
                style="word-break: break-all;">
                <strong>CDN URL:</strong> <a id="cdn-url" href="#" target="_blank"
                    rel="noopener noreferrer">Enter details above</a>
            </div>

        </div>
    </div>

    <script>
        let baseUrl = "{{ config('app.url') }}";

        function generateURL() {
            let service = document.getElementById('service').value;
            let user = document.getElementById('user').value.trim();
            let repo = document.getElementById('repo').value.trim();
            let tag = document.getElementById('tag').value.trim();
            let file = document.getElementById('file').value.trim();

            if (user && repo && tag && file) {
                let url = `${baseUrl}/${service}/${user}/${repo}/${tag}/${file}`;
                document.getElementById('generated-url').textContent = url;
            } else {
                document.getElementById('generated-url').textContent = "⚠️ Please fill all fields.";
            }
        }

        // function generateCDNUrl() {
        //     let domain = document.getElementById('cdn-domain').value.trim();
        //     let path = document.getElementById('cdn-path').value.trim();

        //     if (domain && path) {
        //         let cdnUrl = `${baseUrl}/img/${domain}/${path}`;
        //         document.getElementById('cdn-url').textContent = cdnUrl;
        //     } else {
        //         document.getElementById('cdn-url').textContent = "⚠️ Please enter both domain and file path.";
        //     }
        // }
        function generateCDNUrl() {
            let domainInput = document.getElementById('cdn-domain').value.trim();
            let path = document.getElementById('cdn-path').value.trim();

            // Remove protocol and trailing slash
            domainInput = domainInput.replace(/^https?:\/\//, '').replace(/\/+$/, '');

            // Basic domain validation
            const domainPattern = /^[a-zA-Z0-9.-]+$/;
            if (!domainInput || !domainPattern.test(domainInput)) {
                document.getElementById('cdn-url').textContent =
                    "❌ Invalid domain. Do not include http(s):// or trailing slash.";
                return;
            }

            if (!path) {
                document.getElementById('cdn-url').textContent = "⚠️ Please enter file path.";
                return;
            }

            const cdnUrl = `${baseUrl}/img/${domainInput}/${path}`;
            // document.getElementById('cdn-url').textContent = cdnUrl;
            const cdnAnchor = document.getElementById('cdn-url');
            cdnAnchor.textContent = cdnUrl;
            cdnAnchor.href = cdnUrl;

        }
    </script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
