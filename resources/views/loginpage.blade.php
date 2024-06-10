<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Login</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <link href='https://fonts.googleapis.com/css?family=Inter' rel='stylesheet'>
    <link rel="stylesheet" href="{{ asset('assets/css/loginpage.css') }}">
</head>
<body>

    <div class="container-fluid p-0 heiwid position-relative">
        <div class="position-absolute d-xl-block d-none">
            <img src="{{ asset('assets/images/loginpageimage1.png') }}" alt="" height="500px" width="500px">
        </div>
        <div class="position-absolute dotimg d-xl-block d-none">
            <img src="{{ asset('assets/images/loginpagedotimage.png') }}" alt="" height="140px" width="230px">
        </div>
        <div class="position-absolute img2 d-xl-block d-none">
            <img src="{{ asset('assets/images/loginpageimage2.png') }}" alt="" height="500px" width="500px">
        </div>

        <div class="box">
            <div class="px-5 mb-5">
                <img src="{{ asset('assets/images/loginpagelogo.png') }}" alt="" height="92px" width="350px">
            </div>
            <form action="{{ route('form_submit') }}" method="POST">
                @csrf
                <div class="px-5 py-3">
                    <div class="d-flex justify-content-center mb-3">
                        <span class="headtx">Sign In</span>
                    </div>

                    <div class="d-flex justify-content-center mb-3">
                        <span class="errortx">
                            @if (session()->has('ERROR'))
                                {{ session()->get('ERROR') }}
                            @endif
                            @error('email')
                                {{ $message }}
                            @enderror
                        </span>
                    </div>

                    <div class="mb-4 position-relative">
                        <input type="text" name="email" id="id" placeholder="Your Email" class="inpfield" required oninput="checkInput()" autofocus>
                        <img src="{{ asset('assets/images/mail.svg') }}" alt="" class="atsign">
                    </div>
                    <div class="position-relative">
                        <input type="password" name="password" id="password" placeholder="Password" class="inpfield" required oninput="checkInput()">
                        <img src="{{ asset('assets/images/lockfigma.svg') }}" alt="" class="locksign">
                        <img src="{{ asset('assets/images/eyeshow.svg') }}" alt="eye_password" id="eyeimg" class="eyesign" onclick="Pwicon()">
                    </div>

                    <button class="button my-4" type="submit" id="submit">
                        <span class="butttx">Login</span>
                    </button>
                    <div class="d-flex justify-content-center">
                        <span class="forpass">Forgot Password?</span>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <script>
        function checkInput() {
          // Get the input element
          var id = document.getElementById('id');
          var password = document.getElementById('password');
          // Get the submit button
          var submit = document.getElementById('submit');

          // Check if the input is not empty
          if (id.value.trim() !== '' && password.value.trim() !== '') {
            // Enable the submit button
            // submit.removeAttribute('disabled');
            // Add the 'highlighted' class to the submit button
            submit.classList.add('highlighte');
          } else {
            // Disable the submit button if the input is empty
            // submitButton.setAttribute('disabled', 'disabled');
            // Remove the 'highlighted' class from the submit button
            submit.classList.remove('highlighte');
          }
        }
      </script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js" integrity="sha384-IQsoLXl5PILFhosVNubq5LC7Qb9DXgDA9i+tQ8Zj3iwWAwPtgFTxbJ8NT4GN1R8p" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.min.js" integrity="sha384-cVKIPhGWiC2Al4u+LWgxfKTRIcfu0JTxR+EQDz/bgldoEyl4H0zUF0QKbrJ0EcQF" crossorigin="anonymous"></script>
    <script>
        function Pwicon(){
            var x = document.getElementById("password");
            if (x.type === "password") {
                x.type = "text";
                document.getElementById('eyeimg').src="{{ asset('assets/images/eyehide.svg') }}";
            } else {
                x.type = "password";
                document.getElementById('eyeimg').src="{{ asset('assets/images/eyeshow.svg') }}";
            }
        }
      </script>
</body>
</html>
