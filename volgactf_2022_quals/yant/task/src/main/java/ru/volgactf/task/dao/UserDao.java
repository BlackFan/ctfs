package ru.volgactf.task.dao;

import java.util.List;
import jakarta.persistence.EntityManager;
import jakarta.persistence.PersistenceContext;
import jakarta.persistence.criteria.CriteriaBuilder;
import jakarta.persistence.criteria.CriteriaQuery;
import jakarta.persistence.criteria.Root;
import jakarta.transaction.Transactional;
import org.springframework.stereotype.Repository;
import org.hibernate.Hibernate;
import ru.volgactf.task.model.User;

@Repository
@Transactional
public class UserDao {

    @PersistenceContext
    protected EntityManager entityManager;

    public User register(String username, String password) {
        User user = new User(username, password);
        entityManager.persist(user);
        return user;
    }

    public User login(String name, String pass) {
        CriteriaBuilder cb = entityManager.getCriteriaBuilder();
        CriteriaQuery<User> q = cb.createQuery(User.class);
        Root<User> c = q.from(User.class);
        q.select(c);
        q.where(
                cb.equal(c.get("username"), name),
                cb.equal(c.get("password"), pass)
        );
        List<User> users = entityManager.createQuery(q).getResultList();
        if(users.isEmpty()) {
            return null;
        } else {
            return users.get(0);
        }
    }

    public void update(User user) {
        entityManager.merge(user);
    }

    public void save(User user) {
        entityManager.persist(user);
    }

    public User findByName(String name) {
        CriteriaBuilder cb = entityManager.getCriteriaBuilder();
        CriteriaQuery<User> q = cb.createQuery(User.class);
        Root<User> c = q.from(User.class);
        q.select(c);
        q.where(
                cb.equal(c.get("username"), name)
        );
        List<User> users = entityManager.createQuery(q).getResultList();
        if(users.isEmpty()) {
            return null;
        } else {
            return users.get(0);
        }
    }

    public User getUser(Integer id) {
        User user = entityManager.find(User.class, id);
        if(user != null)
            Hibernate.initialize(user.getNotes());
        return user;
    }
}