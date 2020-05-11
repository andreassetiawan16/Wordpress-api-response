// Allow access to all password protected posts if the context is edit.
if ( 'edit' === $request['context'] ) {
  add_filter( 'post_password_required', '__return_false' );
}

$posts = array();

foreach ( $query_result as $post ) {
  if ( ! $this->check_read_permission( $post ) ) {
    continue;
  }

  $data    = $this->prepare_item_for_response( $post, $request );
  $posts[] = $this->prepare_response_for_collection( $data );
}

<-- THE CODE -->
// get list tags
$tags = get_tags();
$temp_tags = [];
foreach($tags as $tag) {
  array_push($temp_tags, $tag->term_id);
}
// get list category
$categories = get_the_category();
$temp_categories = [];
foreach($categories as $category) {
  array_push($temp_categories, $category->term_id);
}
foreach($posts as $key=> $temp_post) {
  $posts[$key]['categories_name'] = [];
  $posts[$key]['tags_name'] = [];
  foreach($posts[$key]['categories'] as $post_category) {
    $index_cat = array_search($post_category, $temp_categories);
    array_push($posts[$key]['categories_name'], $categories[$index_cat]);
  }
  foreach($posts[$key]['tags'] as $post_tag) {
    $index_tag = array_search($post_tag, $temp_tags);
    array_push($posts[$key]['tags_name'], $tags[$index_tag]);
  }
}
<-- THE CODE -->

// Reset filter.
if ( 'edit' === $request['context'] ) {
  remove_filter( 'post_password_required', '__return_false' );
}

$page        = (int) $query_args['paged'];
$total_posts = $posts_query->found_posts;

if ( $total_posts < 1 ) {
  // Out-of-bounds, run the query again without LIMIT for total count.
  unset( $query_args['paged'] );

  $count_query = new WP_Query();
  $count_query->query( $query_args );
  $total_posts = $count_query->found_posts;
		}
