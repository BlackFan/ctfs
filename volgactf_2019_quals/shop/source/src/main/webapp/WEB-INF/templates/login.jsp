<%@ taglib uri = "http://java.sun.com/jsp/jstl/core" prefix = "c" %>
<!DOCTYPE html>
<html lang="en">

<head>

  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <meta name="description" content="">
  <meta name="author" content="">

  <title>Shop Task</title>
  <link href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.3.1/css/bootstrap.css" rel="stylesheet">
  <link href="/css/login.css" rel="stylesheet">
  <link rel="icon" type="image/png" href="/img/favicon.png" sizes="96x96"/>

</head>

  <body class="text-center">
  <nav class="navbar navbar-expand-lg navbar-dark bg-dark fixed-top">
    <div class="container">
      <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarResponsive" aria-controls="navbarResponsive" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
      </button>
      <div class="collapse navbar-collapse" id="navbarResponsive">
        <ul class="navbar-nav ml-auto">
          <li class="nav-item">
            <a class="nav-link" href="/">Home</a>
          </li>
          <c:if test="${user.balance != null}">
            <li class="nav-item">
              <a class="nav-link" href="/profile">Profile</a>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="/logout">Logout</a>
            </li>
          </c:if>
          <c:if test="${user.balance == null}">
            <li class="nav-item">
              <a class="nav-link" href="/registration">Sign up</a>
            </li>
            <li class="nav-item active">
              <a class="nav-link" href="#">Sign in</a>
            </li>
          </c:if>
        </ul>
      </div>
    </div>
  </nav>

    <form action="/loginProcess" method="POST" class="form-signin">
      <img class="mb-4" src="/img/favicon.png" alt="" width="72" height="72">
      <h1 class="h3 mb-3 font-weight-normal">Sign in</h1>
      <h5 class="h5 mb-3 font-weight-normal"><c:out value="${message}"/></h5>
      <label for="inputName" class="sr-only">User name</label>
      <input name="name" id="inputName" class="form-control" placeholder="User name" required autofocus>
      <label for="inputPassword" class="sr-only">Password</label>
      <input name="pass" type="password" id="inputPassword" class="form-control" placeholder="Password" required>
      <button class="btn btn-lg btn-primary btn-block" type="submit">Sign in</button>
      <p class="mt-5 mb-3 text-muted">VolgaCTF 2019</p>
    </form>
    
  <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.3.1/js/bootstrap.bundle.min.js"></script>

  </body>
</html>
