-- Find the actor_id, first_name and last_name of all actors who have never been in a Sci-Fi film.
-- Order by the actor_id in ascending order.
-- Put your query for Q4 here

SELECT a.actor_id AS 'actor_id', a.first_name AS 'first_name', a.last_name AS 'last_name'
FROM actor a
WHERE a.actor_id NOT IN 
(SELECT a.actor_id FROM actor a
INNER JOIN film_actor fa ON a.actor_id = fa.actor_id
INNER JOIN film f ON fa.film_id = f.film_id
INNER JOIN film_category fc ON f.film_id = fc.film_id
INNER JOIN category c ON fc.category_id = c.category_id
WHERE c.name='Sci-Fi')
GROUP BY a.actor_id
ORDER BY a.actor_id ASC;