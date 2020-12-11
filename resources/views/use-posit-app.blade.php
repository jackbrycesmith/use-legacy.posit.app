<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    {{-- <link rel="stylesheet" href="https://rsms.me/inter/inter.css"> --}}
    <link href="{{ mix('/css/app.css') }}" rel="stylesheet">
    {{-- <script src="{{ mix('/js/manifest.js') }}" defer></script> --}}
    {{-- <script src="{{ mix('/js/vendor.js') }}" defer></script> --}}
    <script src="{{ mix('/js/app.js') }}" defer></script>

    {{-- TODO favicon --}}
    <link rel="icon" href="data:image/svg+xml,<svg xmlns=%22http://www.w3.org/2000/svg%22 viewBox=%220 0 100 100%22><text y=%22.9em%22 font-size=%2290%22>📝</text></svg>">

    {{-- TODO add inter font (maybe even selfhost) --}}
    {{-- <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet"> --}}
    @routes_with_url(['use-posit-app'])

    @production


    <script async src="https://use.posit.app/gtpx/*(d3d3Lmdvb2dsZXRhZ21hbmFnZXIuY29t)*/*(Z3RhZw)*/*(anM)*?id=G-W71MTMF2EW"></script>
    <script>
      window.dataLayer = window.dataLayer || [];
      function gtag(){dataLayer.push(arguments);}
      gtag('js', new Date());

      gtag('config', 'G-W71MTMF2EW');
    </script>
    @endproduction

</head>
<body class="font-sans antialiased">
@inertia
</body>
</html>
