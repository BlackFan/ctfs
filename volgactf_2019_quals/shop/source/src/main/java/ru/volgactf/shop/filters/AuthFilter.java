package ru.volgactf.shop.filters;

import java.io.IOException;
import javax.servlet.FilterChain;
import javax.servlet.ServletException;
import javax.servlet.ServletRequest;
import javax.servlet.ServletResponse;
import javax.servlet.http.HttpServletRequest;
import org.springframework.beans.factory.annotation.Autowired;
import org.springframework.stereotype.Component;
import org.springframework.web.filter.GenericFilterBean;
import ru.volgactf.shop.dao.UserDao;
import ru.volgactf.shop.models.User;

@Component
public class AuthFilter extends GenericFilterBean {
 
    @Autowired
    private UserDao userDao;  
    
    @Override
    public void doFilter
      (ServletRequest request, 
      ServletResponse response, 
      FilterChain chain) throws IOException, ServletException {
        HttpServletRequest req = (HttpServletRequest) request;
        if((req.getSession() != null) && (req.getSession().getAttribute("user_id") != null)) {
            User user = userDao.getUser((int)req.getSession().getAttribute("user_id"));
            if(user != null) {
                req.setAttribute("user", user);
            }
        }
        chain.doFilter(request, response);
    }
}