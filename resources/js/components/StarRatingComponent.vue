<template>
    <span>
        Rating: {{rating | round}} <br />
        <i  v-for="(star, idx) in starRating" :key="idx" :class="star"></i>
    </span>
</template>

<script>
export default {
    name: "StarRatingComponent",

    props: [
        'rating'
    ],

    data: function () {
        return {
            starRating: []
        }
    },

    mounted() {
        let starRating = [];
        var rating = this.rating;
            console.log(rating);

        for (let i = 0; i < 5; i++) {
            console.log(rating);
            if (rating >= 1) {
                starRating.push('fas fa-star');
            } else if (rating >= 0.5) {
                starRating.push('fas fa-star-half-alt');
            } else {
                starRating.push('far fa-star');
            }
            rating--;
        }
        this.starRating = starRating;
    },

    filters: {
        round: function (value) {
            return value.toFixed(2)
        }
    }
}
</script>

<style>

</style>