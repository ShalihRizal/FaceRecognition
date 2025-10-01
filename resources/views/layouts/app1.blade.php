<!DOCTYPE html>
<html lang="en">

<head>
	@include('includes.meta')

	@include('includes.title')

	@include('includes.style')

	<script src="js/settings.js"></script>
</head>

<body data-theme="default" data-layout="fluid" data-sidebar-position="left" data-sidebar-behavior="sticky">
	
	@include('includes.header')

	@include('includes.script')

    @yield('script')

</body>

</html>