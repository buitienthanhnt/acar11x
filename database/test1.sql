select *
from `categories`
where
    exists (
        select `id`
        from `pages`
            inner join `page_categories` on `pages`.`id` = `page_categories`.`page_id`
        where
            `categories`.`id` = `page_categories`.`category_id`
            and `active` = 1
            and `pages`.`deleted_at` is null
    )
    and `active` = 1
    and `categories`.`deleted_at` is null
order by RAND()
limit 4

select *
from `categories`
where
    exists (
        select *
        from `pages`
            inner join `page_categories` on `pages`.`id` = `page_categories`.`page_id`
        where
            `categories`.`id` = `page_categories`.`category_id`
            and `active` = 1
            and `pages`.`deleted_at` is null
    )
    and `active` = 1
    and `categories`.`deleted_at` is null
order by RAND()
limit 4

select
    `pages`.*,
    `page_categories`.`category_id` as `pivot_category_id`,
    `page_categories`.`page_id` as `pivot_page_id`
from `pages`
    inner join `page_categories` on `pages`.`id` = `page_categories`.`page_id`
where
    `page_categories`.`category_id` = 17
    and `active` = 1
    and `pages`.`deleted_at` is null
order by `id` desc
limit 6

select
    `pages`.*,
    `page_categories`.`category_id` as `pivot_category_id`,
    `page_categories`.`page_id` as `pivot_page_id`
from `pages`
    inner join `page_categories` on `pages`.`id` = `page_categories`.`page_id`
where
    `page_categories`.`category_id` = 7
    and `active` = 1
    and `pages`.`deleted_at` is null
order by `id` desc
limit 6

select `type`
from `page_contents`
where
    `page_contents`.`deleted_at` is null
    -- get type distinct:
select distinct
    `type`
from `page_contents`
where
    `page_contents`.`deleted_at` is null

-- select writer list for filter
select *
from `writers`
where
    exists (
        select *
        from `pages`
        where
            `writers`.`id` = `pages`.`writer`
            and `active` = 1
            and `pages`.`deleted_at` is null
    )
    and `active` = 1
    and `writers`.`deleted_at` is null

select `id`, `name`
from `writers`
where
    exists (
        select `id`
        from `pages`
        where
            `writers`.`id` = `pages`.`writer`
            and `active` = 1
            and `pages`.`deleted_at` is null
    )
    and `active` = 1
    and `writers`.`deleted_at` is null

SELECT type, JSON_EXTRACT(value, '$.timeValue') AS timeValue
FROM page_contents
where
    type = 'timeline'
ORDER BY timeValue ASC
LIMIT 6

SELECT 'type', 'key', JSON_EXTRACT(VALUE, '$.timeValue') AS timeValue
FROM `page_contents`
WHERE
    `type` = 'timeline'
    AND DATE_FORMAT(
        JSON_EXTRACT(value, '$.timeValue'),
        '%Y-%m-%d %h:%i:%s'
    ) > NOW()
    AND `page_contents`.`deleted_at` IS NULL
ORDER BY timeValue ASC

select distinct
    *
from `pages`
where
    exists (
        select *
        from `page_contents`
        where
            `pages`.`id` = `page_contents`.`page_id`
            and `type` = ?
            and `page_contents`.`deleted_at` is null
    )
    and exists (
        select *
        from `page_contents`
        where
            `pages`.`id` = `page_contents`.`page_id`
            and `type` = ?
            and DATE_FORMAT(
                JSON_EXTRACT(value, '$.timeValue'),
                '%Y-%m-%d %h:%i:%s'
            ) > NOW()
            and `page_contents`.`deleted_at` is null
    )
    and `active` = ?
    and `pages`.`deleted_at` is null

SELECT DISTINCT
    pages.id,
    JSON_EXTRACT(
        page_contents.`value`,
        '$.timeValue'
    ) AS timeValue
from pages
    LEFT JOIN page_contents ON pages.id = page_contents.page_id
WHERE
    page_contents.`type` = 'timeline'
    AND DATE_FORMAT(
        JSON_EXTRACT(
            page_contents.`value`,
            '$.timeValue'
        ),
        '%Y-%m-%d %h:%i:%s'
    ) > NOW()
ORDER BY timeValue
LIMIT 6

SELECT DISTINCT
    page_id,
    `value`
from page_contents
WHERE
    page_contents.`type` = 'timeline'
    AND DATE_FORMAT(
        JSON_EXTRACT(
            page_contents.`value`,
            '$.timeValue'
        ),
        '%Y-%m-%d %h:%i:%s'
    ) > NOW()
GROUP BY
    page_id
ORDER BY JSON_EXTRACT(
        page_contents.`value`, '$.timeValue'
    ) ASC
LIMIT 7

select DISTINCT pages.*, JSON_EXTRACT(
        page_contents.`value`, '$.timeValue'
    ) AS timeValue
from `pages`
    inner join `page_contents` on `pages`.`id` = `page_contents`.`page_id`
where
    `page_contents`.`type` = 'timeline'
    and DATE_FORMAT(
        JSON_EXTRACT(
            page_contents.`value`,
            '$.timeValue'
        ),
        '%Y-%m-%d %h:%i:%s'
    ) > NOW()
    and `active` = 1
    and `pages`.`deleted_at` is null
group by
    `page_contents`.`page_id`
order by `timeValue` asc
limit 6