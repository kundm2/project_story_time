<template>
    <div class="columns col-gapless">
        <div class="play-canvas col-10 col-mx-auto">
            <div class="card">
                <div class="card-header">
                    <div class="card-title h5">...</div>
                    <div class="card-subtitle text-gray">...</div>
                </div>
                <div class="card-image">
                    <img src="https://placekitten.com/750/367" class="img-responsive">
                </div>
                <div class="card-body">
                    <p class="text-primary">
                        Lorem ipsum, dolor sit amet consectetur adipisicing elit. Harum dolores dolorum adipisci asperiores tempore, officia eos reiciendis obcaecati consectetur ut commodi tempora velit, a debitis at accusantium odit dicta quaerat!
                    </p>
                </div>
                <div class="card-footer">
                    <button class="btn btn-primary">...</button>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    name: "PlayView"
}
</script>

<style>
    .play-canvas {
        width: 750px;
    }
    .card {
        border: 0;
        box-shadow: 0 0.25rem 1rem rgba(48,55,66,.15);
    }
</style>