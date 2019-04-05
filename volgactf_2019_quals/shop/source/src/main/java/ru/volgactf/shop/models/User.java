package ru.volgactf.shop.models;

import java.io.Serializable;
import java.util.List;
import javax.persistence.CascadeType;
import javax.persistence.Entity;
import javax.persistence.GeneratedValue;
import javax.persistence.GenerationType;
import javax.persistence.Id;
import javax.persistence.ManyToMany;

@Entity
public class User implements Serializable {

    @Id
    @GeneratedValue(strategy=GenerationType.IDENTITY)
    private Integer id;
    private String name;
    private String pass;
    private Integer balance;
    @ManyToMany(targetEntity=Product.class, cascade={CascadeType.PERSIST, CascadeType.REFRESH})
    private List<Product> cart;

    public User() {
    }

    public User(String name, String pass, Integer balance) {
        this.name = name;
        this.pass = pass;
        this.balance = balance;
    }

    public Integer getId() {
        return id;
    }

    public void setId(Integer id) {
        this.id = id;
    }

    public String getName() {
        return name;
    }

    public void setName(String name) {
        this.name = name;
    }

    public String getPass() {
        return pass;
    }

    public void setPass(String pass) {
        this.pass = pass;
    }

    public Integer getBalance() {
        return balance;
    }

    public void setBalance(Integer weight) {
        this.balance = weight;
    }

    public List<Product> getCartItems() {
        return cart;
    }

    public void setCartItems(List<Product> cart) {
        this.cart = cart;
    }

    @Override
    public String toString() {
        return "User [id=" + id + ", name=" + name + ", pass=" + pass + ", balance=" + balance + ", cart=" + cart + "]";
    }

}
