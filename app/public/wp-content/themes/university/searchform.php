<form class="search-form" method="get" action="<?= esc_url(site_url('/')); ?>">
    <label class="headline headline--medium" for="s">Perform a New Search:</label>
    <div class="search-form-row">
        <input class="s" placeholder="What are you looking for?" id="s" type="search" name="s">
        <input class="search-submit" type="submit" value="Search">
    </div>
</form>