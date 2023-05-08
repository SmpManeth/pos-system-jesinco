<!-- Container -->
<div class="container">

    <div class="row margin-top-50">
        <div class="col-md-12">

            <section id="not-found" class="center">
                <h2>404 <i class="fa fa-question-circle"></i></h2>
                <p>We're sorry, but the page you were looking for doesn't exist.</p>

                <!-- Search -->
                <div class="row">
                    <?php echo form_open("search", "METHOD='GET'"); ?>
                    <div class="col-lg-8 col-lg-offset-2">
                        <div class="main-search-input gray-style margin-top-50 margin-bottom-10">
                            <div class="main-search-input-item">
                                <input type="text" name="q" placeholder="What are you looking for?" value=""/>
                            </div>
                            <button class="button">Search</button>
                        </div>
                    </div>
                    <?php echo form_close(); ?>
                </div>
                <!-- Search Section / End -->


            </section>

        </div>
    </div>

</div>
<!-- Container / End -->