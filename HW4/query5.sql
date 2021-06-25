-- Find the film_title of all films which feature both KIRSTEN PALTROW and WARREN NOLTE
-- Order the results by film_title in descending order.

SELECT f.title AS 'film_title'
FROM film f
INNER JOIN film_actor fa ON fa.film_id = f.film_id
INNER JOIN actor a ON a.actor_id = fa.actor_id
INNER JOIN film_actor fa1 ON fa1.film_id = f.film_id
INNER JOIN actor a1 ON a1.actor_id = fa1.actor_id
WHERE a.actor_id = (SELECT actor_id FROM actor WHERE first_name='KIRSTEN' && last_name="PALTROW") AND a1.actor_id = (SELECT actor_id FROM actor WHERE first_name='WARREN' && last_name="NOLTE")
GROUP BY f.title
ORDER BY f.title DESC;