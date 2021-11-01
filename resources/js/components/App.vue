<template>
	<div>
		<NavBar />
		<router-view class="p-6" v-slot="{ Component }">
			<transition name="fade" mode="out-in">
				<component :is="Component" />
			</transition>
		</router-view>
	</div>
</template>

<script>
import NavBar from './NavBar.vue'
export default {
	computed: {
		id() {
			return this.$store.getters.id;
		},
		verified() {
			return this.$store.getters.verified;
		},
	},
	components: {
		NavBar
	},
};
</script>


<style>
.fade-enter-active,
.fade-leave-active {
  transition: opacity 0.3s ease;
}

.fade-enter-from,
.fade-leave-to {
  opacity: 0;
}

</style>
