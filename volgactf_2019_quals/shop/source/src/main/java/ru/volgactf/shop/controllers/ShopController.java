package ru.volgactf.shop.controllers;

import java.util.ArrayList;
import java.util.List;
import javax.servlet.http.HttpServletRequest;
import javax.servlet.http.HttpSession;
import org.springframework.beans.factory.annotation.Autowired;
import org.springframework.stereotype.Controller;
import org.springframework.ui.Model;
import org.springframework.web.bind.WebDataBinder;
import org.springframework.web.bind.annotation.InitBinder;
import org.springframework.web.bind.annotation.ModelAttribute;
import org.springframework.web.bind.annotation.RequestMapping;
import org.springframework.web.bind.annotation.RequestParam;
import org.springframework.web.servlet.mvc.support.RedirectAttributes;
import ru.volgactf.shop.dao.ProductDao;
import ru.volgactf.shop.dao.UserDao;
import ru.volgactf.shop.models.Product;
import ru.volgactf.shop.models.User;

@Controller
public class ShopController {

    @Autowired
    private UserDao userDao;
    @Autowired
    private ProductDao productDao;
    
    @InitBinder
    public void initBinder(WebDataBinder binder) {
        binder.setDisallowedFields("balance");
    }
    
    @ModelAttribute("user")
    public User getUser(HttpServletRequest request) {
	return request.getAttribute("user") != null ? (User)request.getAttribute("user") : new User();
    }
    
    @RequestMapping(value={"", "/", "/index"})
    public String index(@ModelAttribute("message") String message, @ModelAttribute("user") User user, Model templateModel) {
        templateModel.addAttribute("products", productDao.geProducts());
        return "shop";
    }
    
    @RequestMapping("/login")
    public String login(@ModelAttribute("message") String message) {
        return "login";
    }
    
    @RequestMapping("/registration")
    public String registration(@ModelAttribute("message") String message) {
        return "registration";
    }
    
    @RequestMapping("/logout")
    public String registration(HttpServletRequest request) {
        HttpSession session = request.getSession();
        session.setAttribute("user_id", null);
        return "redirect:index";
    }

    @RequestMapping("/loginProcess")
    public String login(@RequestParam String name, @RequestParam String pass, Model templateModel, RedirectAttributes redir, HttpServletRequest request) {
        HttpSession session = request.getSession();
        User user = userDao.login(name, pass);
        if(user != null) {
            session.setAttribute("user_id", user.getId());
            redir.addFlashAttribute("message", "Successful login");
            return "redirect:index";
        } else {
            redir.addFlashAttribute("message", "Invalid username or password");
            return "redirect:login";
        }
    }

    @RequestMapping("/registrationProcess")
    public String registration(@RequestParam String name, @RequestParam String pass, Model templateModel, RedirectAttributes redir, HttpServletRequest request) {
        HttpSession session = request.getSession();
        if(userDao.findByName(name) == null) {
            User user = userDao.register(name, pass);  
            session.setAttribute("user_id", user.getId());
            redir.addFlashAttribute("message", "Successful registration");
            return "redirect:index";
        } else {
            redir.addFlashAttribute("message", "User already exists");
            return "redirect:registration";
        }
    }

    @RequestMapping("/profile")
    public String profile(@ModelAttribute("user") User user, Model templateModel, HttpServletRequest request) {
        HttpSession session = request.getSession();
        if(session.getAttribute("user_id") == null)
            return "redirect:index";
        List<Product> cart = new ArrayList();
        user.getCartItems().forEach((p) -> {
            cart.add(productDao.geProduct(p.getId()));
        });
        templateModel.addAttribute("cart", cart);
        return "profile";
    }
         
    @RequestMapping("/buy")
    public String buy(@RequestParam Integer productId, @ModelAttribute("user") User user, RedirectAttributes redir, HttpServletRequest request) {
        HttpSession session = request.getSession();
        if(session.getAttribute("user_id") == null)
            return "redirect:index";
        if(true) {
            redir.addFlashAttribute("message", "Too easy");
            return "redirect:index";
        }
        Product product = productDao.geProduct(productId);
        if(product != null) {
            if(product.getPrice() > user.getBalance()) {
                redir.addFlashAttribute("message", "Not enough money");
            } else {
                user.setBalance(user.getBalance() - product.getPrice());
                user.getCartItems().add(product);
                userDao.update(user);
                redir.addFlashAttribute("message", "Successful purchase");
                return "redirect:profile";
            }
        } else {
            redir.addFlashAttribute("message", "Product not found");
        }
        return "redirect:index";
    }
}
