package ru.volgactf.task.dao;

import jakarta.persistence.EntityManager;
import jakarta.persistence.PersistenceContext;
import jakarta.persistence.criteria.CriteriaBuilder;
import jakarta.persistence.criteria.CriteriaQuery;
import jakarta.persistence.criteria.Root;
import jakarta.transaction.Transactional;
import java.util.List;
import org.springframework.stereotype.Repository;
import ru.volgactf.task.model.Session;

@Repository
@Transactional
public class SessionDao {

    @PersistenceContext
    protected EntityManager entityManager;

    public void save(Session session) {
        entityManager.persist(session);
    }
    
    public boolean isValidSession(String deviceFingerprint, String sessionId) {
        CriteriaBuilder cb = entityManager.getCriteriaBuilder();
        CriteriaQuery<Session> q = cb.createQuery(Session.class);
        Root<Session> c = q.from(Session.class);
        q.select(c);
        q.where(
                cb.equal(c.get("sessionId"), sessionId),
                cb.equal(c.get("deviceFingerprint"), deviceFingerprint)
        );
        List<Session> sessions = entityManager.createQuery(q).getResultList();
        if(sessions.isEmpty()) {
            return false;
        } else {
            return true;
        }
    }
}