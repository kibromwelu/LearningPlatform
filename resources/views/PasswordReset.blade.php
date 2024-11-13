<html>

<head>
    <title>Ahazawi</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>

<body>
    <div class="container mt-4">
        @if(session('status'))
        <div class="alert alert-success">
            {{ session('status') }}
        </div>
        @endif
        <div class="card w-50 mx-auto">
            <div class="card-header text-center  font-weight-bold">
                Ahazawi Password Reset Form
            </div>
            <div class="card-body">
                <form name="add-blog-post-form" id="add-blog-post-form" method="post" action="{{ url('api/auth/reset-password/' . $token) }}">
                    @csrf
                    <div class=" form-group">
                        <label for="exampleInputEmail1">New Password</label>
                        <input type="password" id="title" name="password" class="form-control" required="">
                    </div>
                    <input type="checkbox" id="showPassword" onclick="togglePassword()">
                    <label for="showPassword" style="margin-right: 59;">Show Password</label>

                    <button type="submit" class="btn btn-success mr-0 ">Submit</button>
                </form>
            </div>
        </div>
    </div>
</body>

</html>
<script>
    function togglePassword() {
        var title = document.getElementById("title");
        if (title.type === "password") {
            title.type = 'text';
        } else {
            title.type = 'password';
        }
    }
</script>