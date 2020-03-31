function getUser(guid) {
  if(guid) {
    $.getJSON(`//${api}.volgactf-task.ru/user?guid=${guid}`, function(data) {
      if(!data.success) {
        location.replace('/profile.html');
      } else {
        profile(data.user);
      }
    });
  } else {
    $.getJSON(`//${api}.volgactf-task.ru/user`, function(data) {
      if(!data.success) {
        location.replace('/login.html');
      } else {
        profile(data.user, true);
      }
    }).fail(function (jqxhr, textStatus, error) {console.log(jqxhr, textStatus, error);});
  }
}

function updateUser(user) {
  $.ajax({
    type: 'POST',
    url: `//${api}.volgactf-task.ru/user-update`,
    data: JSON.stringify(user),
    contentType: "application/json",
    dataType: 'json'
  }).done(function(data) {
    if(!data.success) {
      showError(data.error);
    } else {
      location.replace(`/profile.html`);
    }
  });
}

function logout() {
  $.get(`//${api}.volgactf-task.ru/logout`, function(data) {
    location.replace('/login.html');  
  });
}

function profile(user, edit) {
  if(!['/profile.html','/report.php','/editprofile.html'].includes(location.pathname))
    location.replace('/profile.html');
  $('#username').text(user.username);
  $('#username').val(user.username);
  $('#bio').text(user.bio);
  $('#bio').val(user.bio);
  $('#avatar').attr('src', `//static.volgactf-task.ru/${user.avatar}`);
  if(edit) {
    $('#editProfile').removeClass("d-none");
  }
  $('.nav-item .nav-link[href="/login.html"]').addClass("d-none");
  $('.nav-item .nav-link[href="/register.html"]').addClass("d-none");
  $('.nav-item .nav-link[href="/profile.html"]').removeClass("d-none");
  $('.nav-item .nav-link[href="/logout.html"]').removeClass("d-none");
}

function replaceForbiden(str) {
  return str.replace(/[ !"#$%&´()*+,\-\/:;<=>?@\[\\\]^_`{|}~]/g,'').replace(/[^\x00-\x7F]/g, '?');
}

function showError(error) {
   $('#error').removeClass("d-none").text(error);
}

$(document).ready(function() {
  api = 'api';
  if(Cookies.get('api_server')) {
    api = replaceForbiden(Cookies.get('api_server'));
  } else {
    Cookies.set('api_server', api, {secure: true});
  }

  $.ajaxSetup({
    xhrFields: {
      withCredentials: true
    }
  });

  $('#logForm').submit(function(event) {
    event.preventDefault();
    $.ajax({
      type: 'POST',
      url: `//${api}.volgactf-task.ru/login`,
      data: JSON.stringify({username: $('#username').val(), password: $('#password').val()}),
      contentType: "application/json",
      dataType: 'json'
    }).done(function(data) {
      if(!data.success) {
        showError(data.error);
      } else {
        location.replace(`/profile.html?guid=${data.guid}`);
      }
    });
  });

  $('#regForm').submit(function(event) {
    event.preventDefault();
    $.ajax({
      type: 'POST',
      url: `//${api}.volgactf-task.ru/register`,
      data: JSON.stringify({username: $('#username').val(), password: $('#password').val()}),
      contentType: "application/json",
      dataType: 'json'
    }).done(function(data) {
      if(!data.success) {
        showError(data.error);
      } else {
        location.replace(`/profile.html`);
      }
    });
  });

  $('#avatar').on('change',function(){
    $(this).next('.custom-file-label').text($(this).prop('files')[0].name);
  });

  $('#editForm').submit(function(event) {
    event.preventDefault();
    b64Avatar = '';
    mime = '';
    bio = $('#bio').val();
    avatar = $('#avatar').prop('files')[0];
    if(avatar) {
      reader = new FileReader();
      reader.readAsDataURL(avatar);
      reader.onload = function(e) {
        b64Avatar = reader.result.split(',')[1];
        mime = avatar.type;
        updateUser({avatar: b64Avatar, type: mime, bio: bio});
      }  
    } else {
      updateUser({bio: bio});
    }
  });

  params = new URLSearchParams(location.search);

  if(['/','/index.html','/profile.html','/report.php','/editprofile.html'].includes(location.pathname)) {
    getUser(params.get('guid'));
  }
  if(['/logout.html'].includes(location.pathname)) {
    logout();
  }
});