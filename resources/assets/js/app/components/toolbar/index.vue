<template>
    <v-toolbar app dark dense fixed clipped-left>
        <v-btn icon v-if="inDashboardLayout" @click.native="toggleDashboardMenu()">
            <v-icon class="lime--text text--darken-1">dashboard</v-icon>
        </v-btn>

        <v-toolbar-title v-if="!inDashboardLayout || $vuetify.breakpoint.smAndUp">
            <!-- Show app name on md and up-->
            <router-link
                    :to="{name: 'home'}"
                    class="teal--text text--accent-2"
                    style="text-decoration: none;"
            >Mirkt
            </router-link>
        </v-toolbar-title>

        <!-- Links -->
        <links-toolbar-items/>

        <v-spacer/>

        <!-- Search articles -->
        <search-articles-toolbar-items/>

        <!-- If user is logged in -->
        <user-toolbar-items v-if="User.id"/>

        <!-- If user not logged in -->
        <guest-toolbar-items v-else/>
    </v-toolbar>
</template>
<script>
	import GuestToolbarItems          from "./toolbar-items/guest-toolbar-items";
	import LinksToolbarItems          from "./toolbar-items/links-toolbar-items";
	import SearchArticlesToolbarItems from "./toolbar-items/search-articles-toolbar-items";
	import UserToolbarItems           from "./toolbar-items/user-toolbar-items";

	export default {
		name: 'toolbar',
		components: {SearchArticlesToolbarItems, LinksToolbarItems, GuestToolbarItems, UserToolbarItems},
		props: {
			value: Boolean,
			inDashboardLayout: Boolean
		},
		data() {
			return {
				User                   : window.USER,
				isLoginDialogVisible   : false,
				isRegisterDialogVisible: false,
			}
		},
		methods: {
			toggleDashboardMenu() {
				this.$emit('input', !this.value);
			}
		}

	}
</script>  