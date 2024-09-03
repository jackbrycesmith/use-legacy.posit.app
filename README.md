<p align="center">
  <a href="https://posit.app">
    <img src="https://posit.app/posit-logo-full-colour.svg" width="55%" alt="Posit.app logo">
  </a>
</p>

<h1 align="center">
  Posit.app (legacy version)
</h1>

<p align="center">
  <strong>
    <a href="https://use-legacy.posit.app" target="_blank" rel="noopener">https://use-legacy.posit.app</a>
  </strong>
</p>

<p align="center">
  <video src="https://github.com/user-attachments/assets/f5209538-660d-4999-b731-4cbd0b8ce38f" width="100%"></video>
</p>

## What is posit.app (legacy version)?

- A platform for you to **create _'Posits'_**: concise and easily digestible sales proposals.
- Write the contents within a series of **linked card sections**, which positively constrain you to maintain focus and clarity.
- Include a **short 30-second video recording**, making your proposals more personal and engaging.
- Option to **require payment** for the recipient to accept your proposal, **promoting commitment and action**.
- **Secure publishing** is enabled by default while **balancing good UX**: recipients can reuse their unique access code to view all Posits received from the same publisher.

## Tech Stack

- Backend:
    - [Laravel](https://laravel.com/) adopting a [Inertia.js](https://inertiajs.com/) based approach
    - Codebase organised with ['Laravel Actions'](https://github.com/lorisleiva/laravel-actions)
    - [Pest Test Suite](https://pestphp.com/)
    - [Stripe Connect](https://stripe.com/connect) integration
- Frontend:
    - [Vue.js](https://vuejs.org/) + [Tailwind CSS](https://tailwindcss.com/)
    - [Tiptap Editor](https://github.com/ueberdosis/tiptap)
    - [Xstate](https://xstate.js.org/) to help implement the more involved UI interactions (e.g. recording intro videos) in a clean & reliable manner
- Misc: [FFmpeg](https://www.ffmpeg.org/) video processing in background jobs, notifying the frontend of updates in realtime with websockets
