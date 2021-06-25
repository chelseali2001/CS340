-- We want to find out how many of each category of film ED CHASE has starred in.

-- So return a table with "category_name" and the count of the "number_of_films" that ED was in that category.

-- Your query should return every category even if ED has been in no films in that category

-- Order by the category name in ascending order.

SELECT c.name AS 'category_name', COUNT(a.actor_id) AS 'number_of_films'
FROM category c
LEFT JOIN film_category fc ON c.category_id = fc.category_id
LEFT JOIN film f ON f.film_id = fc.film_id
LEFT JOIN film_actor fa ON fa.film_id = f.film_id
LEFT JOIN actor a ON (a.actor_id = fa.actor_id) AND (a.first_name = 'ED' AND a.last_name = 'CHASE')
GROUP BY c.category_id
ORDER BY c.name ASC;