<!DOCTYPE html>
<html lang="en">

<head>
	@include('includes.meta')

	@include('includes.title')

	@include('includes.style')

</head>

<body data-theme="default" data-layout="fluid" data-sidebar-position="left" data-sidebar-behavior="sticky">
	<div class="main d-flex justify-content-center w-100">
        <main class="content d-flex p-0">
            @yield('content')
        </main>
	</div>
</body>

</html>
