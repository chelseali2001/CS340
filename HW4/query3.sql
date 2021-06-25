-- Find the actor_id first_name, last_name and total_combined_film_length 
-- of Sci-Fi films for every actor.
-- That is the result should list the actor ids, names of all of the actors(even if an actor has not been in any Sci-Fi films) 
-- and the total length of Sci-Fi films they have been in.
-- Look at the category table to figure out how to filter data for Sci-Fi films.
-- Order your results by the actor_id in descending order.
-- Put query for Q3 here

SELECT a1.actor_id, a1.first_name, a1.last_name, IFNULL(temp.total_combined_film_length, 0) AS 'total_combined_film_length'
FROM actor a1
LEFT OUTER JOIN (
SELECT a.actor_id AS 'actor_id', SUM(f.length) AS 'total_combined_film_length'
FROM actor a
INNER JOIN film_actor fa ON a.actor_id = fa.actor_id
INNER JOIN film f ON fa.film_id = f.film_id
INNER JOIN film_category fc ON f.film_id = fc.film_id
INNER JOIN category c ON fc.category_id = c.category_id AND c.name = 'Sci-Fi'
GROUP BY a.actor_id) temp ON a1.actor_id = temp.actor_id
ORDER BY a1.actor_id DESC;