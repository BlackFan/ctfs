package ru.volgactf.task.filter;

import jakarta.servlet.FilterChain;
import jakarta.servlet.ServletException;
import jakarta.servlet.http.HttpFilter;
import jakarta.servlet.http.HttpServletRequest;
import jakarta.servlet.http.HttpServletResponse;
import java.io.IOException;
import java.util.Arrays;
import java.util.List;
import java.util.stream.Collectors;
import org.json.JSONObject;
import org.springframework.beans.factory.annotation.Autowired;
import org.springframework.http.HttpMethod;
import org.springframework.stereotype.Component;
import org.springframework.web.context.request.RequestAttributes;
import org.springframework.web.context.request.RequestContextHolder;
import ru.volgactf.task.dao.SessionDao;
import ru.volgactf.task.utils.Utils;

@Component
public class SecurityFilter extends HttpFilter {
    
    @Autowired
    private SessionDao sessionDao;

    @Override
    protected void doFilter(HttpServletRequest servletRequest, HttpServletResponse response, FilterChain filterChain) throws ServletException, IOException {
        MultiReadHttpServletRequest request = new MultiReadHttpServletRequest(servletRequest);
        RequestAttributes requestAttributes = RequestContextHolder.getRequestAttributes();
        Integer sessionUserID = (Integer)(requestAttributes.getAttribute("user_id", RequestAttributes.SCOPE_SESSION));
        Integer requestUserID = null;
        
        if (HttpMethod.POST.matches(request.getMethod())) {
            try {
                String body = request.getReader().lines().collect(Collectors.joining(System.lineSeparator()));
                JSONObject jsonObject = new JSONObject(body);
                requestUserID = jsonObject.getInt("userID");
            } catch (Exception e) {
            }
        }
        
        if(checkPath(request.getRequestURI())) {
            filterChain.doFilter(request, response);
            return;
        }
        
        if((sessionUserID != null) && ((requestUserID == null) || sessionUserID.equals(requestUserID))) {
            String deviceFingerprint = Utils.getMd5(requestUserID, request.getHeader("User-Agent"), request.getRemoteAddr());
            if(sessionDao.isValidSession(deviceFingerprint, requestAttributes.getSessionId())) {
                filterChain.doFilter(request, response);
            } else {
                response.sendError(HttpServletResponse.SC_FORBIDDEN, "Unauthorized");
            }
        } else {
            response.sendError(HttpServletResponse.SC_FORBIDDEN, "Unauthorized");
        }
    }
    
    private boolean checkPath(String path) {
        List<String> allowedPaths = Arrays.asList("/", "/login", "/signup");
        return allowedPaths.contains(path);
    }

}
