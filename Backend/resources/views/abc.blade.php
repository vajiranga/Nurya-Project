<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Advanced Portfolio</title>
  <style>
    * {
      margin: 0;
      padding: 0;
      box-sizing: border-box;
    }
    body {
      font-family: Arial, sans-serif;
      background-color: #f4f4f9;
      color: #333;
      line-height: 1.6;
    }
    header {
      background: #0078D4;
      color: #fff;
      padding: 1.5rem 0;
      text-align: center;
    }
    header h1 {
      font-size: 2.5rem;
    }
    nav {
      display: flex;
      justify-content: center;
      background: #0056A1;
      padding: 0.5rem 0;
    }
    nav a {
      color: #fff;
      text-decoration: none;
      margin: 0 1rem;
      font-weight: bold;
      transition: color 0.3s;
    }
    nav a:hover {
      color: #FFD700;
    }
    .container {
      max-width: 1200px;
      margin: 2rem auto;
      padding: 0 1rem;
    }
    .about, .projects, .contact {
      margin-bottom: 3rem;
    }
    .about h2, .projects h2, .contact h2 {
      margin-bottom: 1rem;
      color: #0078D4;
    }
    .projects .project {
      display: flex;
      justify-content: space-between;
      margin-bottom: 1.5rem;
      background: #fff;
      box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
      padding: 1rem;
      border-radius: 5px;
    }
    .projects .project img {
      max-width: 150px;
      border-radius: 5px;
    }
    .projects .project .info {
      flex: 1;
      margin-left: 1rem;
    }
    footer {
      text-align: center;
      padding: 1rem 0;
      background: #0056A1;
      color: #fff;
      margin-top: 2rem;
    }
    .scroll-up-btn {
      position: fixed;
      bottom: 20px;
      right: 20px;
      background: #0078D4;
      color: #fff;
      padding: 10px 15px;
      border: none;
      border-radius: 50%;
      font-size: 20px;
      cursor: pointer;
      display: none;
    }
    .scroll-up-btn:hover {
      background: #0056A1;
    }
  </style>
</head>
<body>
  <header>
    <h1>My Portfolio</h1>
  </header>

  <nav>
    <a href="#about">About</a>
    <a href="#projects">Projects</a>
    <a href="#contact">Contact</a>
  </nav>

  <div class="container">
    <section id="about" class="about">
      <h2>About Me</h2>
      <p>Hello! I'm a passionate web developer with a knack for creating sleek, responsive, and user-friendly websites. I love coding and solving real-world problems through technology.</p>
    </section>

    <section id="projects" class="projects">
      <h2>Projects</h2>
      <div class="project">
        <img src="https://via.placeholder.com/150" alt="Project 1">
        <div class="info">
          <h3>Project 1</h3>
          <p>A description of the first project goes here. This could include technologies used and the purpose of the project.</p>
        </div>
      </div>
      <div class="project">
        <img src="https://via.placeholder.com/150" alt="Project 2">
        <div class="info">
          <h3>Project 2</h3>
          <p>A description of the second project goes here. Highlight its features and functionality.</p>
        </div>
      </div>
    </section>

    <section id="contact" class="contact">
      <h2>Contact Me</h2>
      <p>If you'd like to get in touch, feel free to drop me an email at <a href="mailto:example@example.com">example@example.com</a>.</p>
    </section>
  </div>

  <footer>
    <p>&copy; 2025 My Portfolio. All rights reserved.</p>
  </footer>

  <button class="scroll-up-btn" id="scrollUpBtn">&#8679;</button>

  <script>
    // Scroll-Up Button Functionality
    const scrollUpBtn = document.getElementById("scrollUpBtn");
    window.onscroll = function () {
      if (document.body.scrollTop > 100 || document.documentElement.scrollTop > 100) {
        scrollUpBtn.style.display = "block";
      } else {
        scrollUpBtn.style.display = "none";
      }
    };

    scrollUpBtn.onclick = function () {
      window.scrollTo({ top: 0, behavior: "smooth" });
    };
  </script>
</body>
</html>
