<template>
    <div class="columns col-gapless drawing-canvas">
        <div class="column col-12">
            <p class="h5">
                {{ story.parts[story.parts.length - 1].data.content}}
            </p>
        </div>
        <div class="column col-12">
            <div class="divider text-center" data-content="How would you draw this?"></div>
        </div>
        <div class="column col-12">
            <canvas @mousedown="startPainting" @mouseup="finishedPainting" @mousemove="draw"
            id="drawing" :width="canvasDimensions.width" :height="canvasDimensions.height"></canvas>
        </div>
        <div class="column col-12">
            <ul class="drawing-tools">
                <li>
                    <input class="form-input square-input" id="input-example-15" type="color" v-model="stroke.color">
                </li>
                <li class="color-slider">
                    <input class="slider" type="range" min="1" max="20" v-model="stroke.size">
                </li>
                <li>
                    <a class="btn form-input square-input" @click="setStrokeColor('#ffffff')">
                        <i class="fas fa-eraser"></i>
                    </a>
                </li>
                <li>
                    <div class="divider-vert"></div>
                </li>
                <li v-for="(color, idx) in colors" :key="idx">
                    <a class="btn form-input square-input tooltip tooltip-bottom" :data-tooltip="color.name" @click="setStrokeColor(color.rgb)">
                        <i class="fas fa-circle" v-bind:style="{ color: color.rgb }"></i>
                    </a>
                </li>
            </ul>
        </div>
    </div>
</template>

<script>
export default {
    name: 'DrawingGameComponent',

    props: [
        'story'
    ],
    
    mounted() {
        this.canvas = document.getElementById('drawing');
        this.ctx = this.canvas.getContext('2d');
        this.canvasDimensions.width = this.canvas.scrollWidth;
        this.canvasDimensions.height = this.canvas.scrollHeight;
    },

    data: function () {
        return {
            painting: false,
            canvas: null,
            ctx: null,
            canvasDimensions: {
                'width': 700,
                'height': 400
            },
            stroke: {
                'color': '#000000',
                'size': 7
            },
            colors: [
                { 'name': 'Blue', 'rgb': '#3490dc' },
                { 'name': 'Indigo', 'rgb': '#5755d9' },
                { 'name': 'Purple', 'rgb': '#9561e2' },
                { 'name': 'Pink', 'rgb': '#f66d9b' },
                { 'name': 'Red', 'rgb': '#e3342f' },
                { 'name': 'Orange', 'rgb': '#f6993f' },
                { 'name': 'Yellow', 'rgb': '#ffed4a' },
                { 'name': 'Green', 'rgb': '#38c172' },
                { 'name': 'Teal', 'rgb': '#4dc0b5' },
                { 'name': 'Cyan', 'rgb': '#6cb2eb' }
            ]
        }
    },

    methods: {
        startPainting(e) {
            this.painting = true;
            this.draw(e);
        },
        finishedPainting() {
            this.painting = false;
            this.ctx.beginPath();
            this.$emit('update:content', this.canvas.toDataURL('image/png'));
        },
        draw(e) {
            if(!this.painting) return;

            this.ctx.lineCap = 'round';
            this.ctx.strokeStyle = this.stroke.color;
            this.ctx.lineWidth = this.stroke.size;
            
            this.ctx.lineTo(this.drawPositionX(e), this.drawPositionY(e));
            this.ctx.stroke();

            this.ctx.beginPath();
            this.ctx.moveTo(this.drawPositionX(e), this.drawPositionY(e));
        },
        drawPositionX(e) {
            return e.clientX - this.canvasPosition.left;
        },
        drawPositionY(e) {
            return e.clientY - this.canvasPosition.top;
        },
        setStrokeColor(color) {
            this.stroke.color = color;
        }
    },

    computed: {
        windowHeight() {
            return window.innerHeight;
        },
        windowWidth() {
            return window.innerWidth;
        },
        canvasPosition() {
            return this.canvas.getBoundingClientRect();
        }
    }
}
</script>

<style>

</style>