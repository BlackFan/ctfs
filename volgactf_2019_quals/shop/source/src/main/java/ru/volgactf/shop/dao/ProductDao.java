package ru.volgactf.shop.dao;

import java.util.List;
import javax.persistence.EntityManager;
import javax.persistence.PersistenceContext;
import javax.transaction.Transactional;
import org.springframework.dao.DataAccessException;
import org.springframework.stereotype.Repository;
import ru.volgactf.shop.models.Product;

@Transactional
@Repository
public class ProductDao {

    @PersistenceContext
    protected EntityManager entityManager;

    public void save(Product product) {
        entityManager.persist(product);
    }

    public List<Product> geProducts() throws DataAccessException {
        return entityManager.createQuery("Select t from Product t").getResultList();
    }

    public Product geProduct(Integer id) throws DataAccessException {
        Product product = entityManager.find(Product.class, id);
        return product;
    }
}