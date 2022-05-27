package ru.volgactf.task.controller;

import jakarta.servlet.http.HttpServletRequest;
import jakarta.servlet.http.HttpServletResponse;
import org.springframework.beans.factory.annotation.Autowired;
import org.springframework.http.MediaType;
import org.springframework.stereotype.Controller;
import org.springframework.web.bind.annotation.PostMapping;
import org.springframework.web.bind.annotation.RequestBody;
import org.springframework.web.bind.annotation.ResponseBody;
import org.springframework.web.context.request.RequestAttributes;
import org.springframework.web.context.request.RequestContextHolder;
import ru.volgactf.task.dao.SessionDao;
import ru.volgactf.task.dao.UserDao;
import ru.volgactf.task.model.Note;
import ru.volgactf.task.model.Session;
import ru.volgactf.task.model.User;
import ru.volgactf.task.request.AddNoteRequest;
import ru.volgactf.task.request.BaseRequest;
import ru.volgactf.task.request.LoginRequest;
import ru.volgactf.task.response.BaseResponse;
import ru.volgactf.task.response.LoginResponse;
import ru.volgactf.task.response.NotesResponse;
import ru.volgactf.task.utils.Utils;

@Controller
public class HomeController {

    @Autowired
    private UserDao userDao;
    @Autowired
    private SessionDao sessionDao;

    @PostMapping(value = "/signup", produces = MediaType.APPLICATION_JSON_VALUE)
    public @ResponseBody
    LoginResponse signup(HttpServletRequest httpRequest, @RequestBody LoginRequest request) {
        RequestAttributes requestAttributes = RequestContextHolder.getRequestAttributes();
        LoginResponse response = new LoginResponse();
        if (request.getPassword().length() < 6) {
            response.setResult("error");
            response.setError("Password must be at least 6 characters long");
            return response;
        }
        if (userDao.findByName(request.getUsername()) == null) {
            User user = userDao.register(request.getUsername(), request.getPassword());
            RequestContextHolder.getRequestAttributes().setAttribute("user_id", user.getId(), RequestAttributes.SCOPE_SESSION);
            String deviceFingerprint = Utils.getMd5(user.getId(), httpRequest.getHeader("User-Agent"), httpRequest.getRemoteAddr());
            sessionDao.save(new Session(deviceFingerprint, requestAttributes.getSessionId()));
            response.setResult("ok");
            response.setUserID(user.getId().toString());
        } else {
            response.setResult("error");
            response.setError("User already exists");
        }
        return response;
    }

    @PostMapping(value = "/login", produces = MediaType.APPLICATION_JSON_VALUE)
    public @ResponseBody
    LoginResponse login(HttpServletRequest httpRequest, HttpServletResponse httpResponse, @RequestBody LoginRequest request) {
        RequestAttributes requestAttributes = RequestContextHolder.getRequestAttributes();
        User user = userDao.login(request.getUsername(), request.getPassword());
        LoginResponse response = new LoginResponse();
        if (user != null) {
            RequestContextHolder.getRequestAttributes().setAttribute("user_id", user.getId(), RequestAttributes.SCOPE_SESSION);
            String deviceFingerprint = Utils.getMd5(user.getId(), httpRequest.getHeader("User-Agent"), httpRequest.getRemoteAddr());
            sessionDao.save(new Session(deviceFingerprint, requestAttributes.getSessionId()));
            response.setResult("ok");
            response.setUserID(user.getId().toString());
        } else {
            response.setResult("error");
            response.setError("Username or password is incorrect");
        }
        return response;
    }

    @PostMapping(value = "/notes", produces = MediaType.APPLICATION_JSON_VALUE)
    public @ResponseBody NotesResponse notes(@RequestBody BaseRequest request) {
        NotesResponse response = new NotesResponse();
        User user = userDao.getUser(request.getUserID());
        if(user == null) {
            response.setResult("error");
            response.setError("Something wrong");
        } else {
            response.setResult("ok");
            response.setNotes(user.getNotes());
        }
        return response;
    }

    @PostMapping(value = "/add", produces = MediaType.APPLICATION_JSON_VALUE)
    public @ResponseBody BaseResponse add(@RequestBody AddNoteRequest request) {
        BaseResponse response = new BaseResponse();
        User user = userDao.getUser(request.getUserID());
        if(user == null) {
            response.setResult("error");
            response.setError("Something wrong");
        } else {
            user.addNote(new Note(request.getText()));
            userDao.update(user);
            response.setResult("ok");
        }
        return response;
    }
}
