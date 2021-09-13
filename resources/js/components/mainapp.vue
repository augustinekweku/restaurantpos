<template>
    <div>
        <div :class="darkMode ? 'darkMode' : ''" id="main-wrapper">
            <!-- Menu -->
            <nav
                v-if="
                    $route.path != '/login' &&
                        $route.path != '/register' &&
                        $route.path != '/resetPassword' &&
                        $route.path != '/forgetPassword'
                "
                :class="
                    sidebar
                        ? '_navbar _fixed_top _navbar_light _navbar_wap _mini_navbar'
                        : '_navbar _fixed_top _navbar_light _navbar_wap'
                "
            >
                <div class="_navbar_left">
                    <div class="_navbar_logo d-flex">
                        <div>
                        <h2 class="_navbar_logo_img">S</h2>
                        </div>
                        <div>
                        <h3 class="_navbar_logo_img_text">somewhere</h3>
                        </div>
                    </div>

                    <!-- TOP BAR -->
                    <div class="_navbar_left_button">
                        <div
                            @click="sidebar = !sidebar"
                            class="_navbar_left_icon"
                        >
                            <Icon type="md-list" />
                        </div>

                        <div
                            @click="mobSidebar = !mobSidebar"
                            class="_navbar_left_icon _mob_icon"
                        >
                            <Icon type="md-list" />
                        </div>
                    </div>

                    <div class="_navbar_search">
                        <div class="_navbar_search_main_all">
                            <div class="_navbar_search_main">
                                <Icon type="ios-search-outline" />
                                <input type="text" placeholder="Search.." />
                                <div class="outline"></div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- TOP BAR RIGHT -->
                <div class="_navbar_right">
                    <ul class="_navbar_right_list">
                        <li class="_mosearch" @click="mobSearchOpen = true">
                            <Icon type="ios-search-outline" />
                        </li>

                        <!-- NOTIFICATIONS AND MESSAGES ON THE TOP BAR RIGHT -->
                        <div class="_navbar_right">
                            <div class="d-flex justify-content-end">
                            <div>Hiya, {{ userSession.firstName}}</div>

                            </div>
                            <ul class="_navbar_right_list">
                                <li
                                    class="_mosearch d-none"
                                    @click="mobSearchOpen = true"
                                >
                                    <Icon type="ios-search-outline" />
                                </li>

                                <li class="_nav_pro d-none">
                                    <Dropdown
                                        trigger="click"
                                        placement="bottom-end"
                                    >
                                        <a href="javascript:void(0)">
                                            <div class="_nav_pro_pic">
                                                <img
                                                    class="_nav_pro_img"
                                                    src="/static/img/photo.jpg"
                                                    alt=""
                                                    title=""
                                                />
                                            </div>
                                        </a>
                                        <DropdownMenu slot="list">
                                            <div class="_nav_pro_main">
                                                <div class="_nav_pro_top">
                                                    <div
                                                        class="_nav_pro_top_pic"
                                                    >
                                                        <img
                                                            class="_nav_pro_top_img"
                                                            src="/static/img/ONE.jpg"
                                                            title=""
                                                            alt=""
                                                        />
                                                    </div>

                                                    <div
                                                        class="_nav_pro_top_details"
                                                    >
                                                        <p
                                                            class="_nav_pro_top_name"
                                                        >
                                                            Steave Jobs
                                                        </p>
                                                        <p
                                                            class="_nav_pro_top_email"
                                                        >
                                                            SteaveJobs@gmail.com
                                                        </p>
                                                    </div>
                                                </div>

                                                <div
                                                    class="_nav_pro_list_main _1border_color"
                                                >
                                                    <ul class="_nav_pro_list">
                                                        <li>
                                                            <router-link
                                                                to="/profile"
                                                            >
                                                                <Icon
                                                                    type="md-person"
                                                                />
                                                                <p
                                                                    class="_nav_pro_list_text"
                                                                >
                                                                    My Profile
                                                                </p>
                                                            </router-link>
                                                        </li>

                                                        <li>
                                                            <a href="">
                                                                <Icon
                                                                    type="md-cash"
                                                                />
                                                                <p
                                                                    class="_nav_pro_list_text"
                                                                >
                                                                    My Balance
                                                                </p>
                                                            </a>
                                                        </li>

                                                        <li>
                                                            <a href="">
                                                                <Icon
                                                                    type="md-mail"
                                                                />
                                                                <p
                                                                    class="_nav_pro_list_text"
                                                                >
                                                                    My Inbox
                                                                </p>
                                                            </a>
                                                        </li>
                                                    </ul>
                                                </div>

                                                <div
                                                    class="_nav_pro_list_main _1border_color"
                                                >
                                                    <ul class="_nav_pro_list">
                                                        <li>
                                                            <a href="">
                                                                <Icon
                                                                    type="ios-cog"
                                                                />
                                                                <p
                                                                    class="_nav_pro_list_text"
                                                                >
                                                                    Account
                                                                    Setting
                                                                </p>
                                                            </a>
                                                        </li>
                                                    </ul>
                                                </div>

                                                <div
                                                    class="_nav_pro_list_main _1border_color"
                                                >
                                                    <ul class="_nav_pro_list">
                                                        <li>
                                                            <a href="">
                                                                <Icon
                                                                    type="ios-exit"
                                                                />
                                                                <p
                                                                    class="_nav_pro_list_text"
                                                                >
                                                                    Log Out
                                                                </p>
                                                            </a>
                                                        </li>
                                                    </ul>
                                                </div>
                                            </div>
                                        </DropdownMenu>
                                    </Dropdown>
                                </li>
                            </ul>
                        </div>

                        <!-- AVATAR WITH DROPDOWN -->
                    </ul>
                </div>

                <!-- Mobile Search -->
                <div
                    class="_mob_search"
                    :class="mobSearchOpen ? '_mob_search_open' : ''"
                >
                    <div class="_mob_search_main">
                        <div class="_navbar_search_main_all">
                            <div class="_navbar_search_main">
                                <Icon type="ios-search-outline" />
                                <input type="text" placeholder="Search.." />
                                <div class="outline"></div>
                            </div>
                        </div>
                    </div>
                    <div class="_mob_search_close">
                        <Icon @click="mobSearchOpen = false" type="md-close" />
                    </div>
                </div>
                <!-- Mobile Search -->
            </nav>
            <!-- Menu end -->

            <!-- Sidebar  -->
            <aside
                v-if="
                    $route.path != '/login' &&
                        $route.path != '/register' &&
                        $route.path != '/resetPassword' &&
                        $route.path != '/forgetPassword'
                "
                :class="[
                    sidebar ? '_left_sidebar _hide_sidebar' : '_left_sidebar',
                    lightSidebar ? '_light_sidebar' : '',
                    darkSidebar ? '_dark_sidebar' : '',
                    mobSidebar ? '_mobSidebarOpen' : ''
                ]"
            >
                <div class="_left_sidebar_main">
                    <!-- PROFILE PIC WITH NAME AND ROLE -->

                    <div class="_left_sidebar_menu _1scrollbar">
                        <Menu :theme="theme3" :active-name="activeRoute">
                            <MenuGroup title="Dashboard">
                                <p class="_group_name">
                                    <Icon type="ios-more" />
                                </p>
                                <Submenu v-if="$store.state.user.userType ==='waiter' 
                                || $store.state.user.userType === 'admin'" name="waiter">
                                <template slot="title">
                                    <Icon type="md-person" />
                                    <span class="submenu_text">Waiter</span>
                                </template>
                                <MenuItem to="/pos-page" name="pos">
                                    <Icon type="md-cart" style="color:#275655 !important"/>
                                    <span class="submenu_text">POS</span>
                                </MenuItem>
                                <MenuItem
                                    to="/ready-orders"
                                    name="ready-orders"
                                    
                                >
                                    <Icon type="md-alarm" style="color:green !important" />
                                    <span class="submenu_text"
                                        > Ready Orders    <Tag v-if="readyOrdersCount" color="error">{{readyOrdersCount}}</Tag></span
                                    >
                                </MenuItem>
                                </Submenu>

                                <Submenu v-if="$store.state.user.userType === 'cook' ||
                                $store.state.user.userType === 'admin'"
                                name="cook">
                                <template slot="title">
                                    <Icon type="md-person" />
                                    <span class="submenu_text">Chef</span>
                                </template>
                                <MenuItem
                                    to="/orders"
                                    name="orders"
                                >
                                    <Icon type="md-clock" style="color:#2d8a99 !important" />
                                    <span class="submenu_text"
                                        > Orders <Tag v-if="orderNotificationsCount>0" color="error">{{orderNotificationsCount}}</Tag></span
                                    >
                                </MenuItem>
                                </Submenu>



                                <MenuItem v-if="$store.state.user.userType === 'admin'"
                                to="/admin-users" name="users">
                                    <Icon type="md-people" />
                                    <span class="submenu_text">Users</span>
                                </MenuItem>
                                <MenuItem v-if="$store.state.user.userType === 'cook' ||
                                $store.state.user.userType === 'admin'" 
                                to="/items" name="items">
                                    <Icon type="md-pizza" style="color:orange !important"/>
                                    <span class="submenu_text">Items</span>
                                </MenuItem>
                                <MenuItem v-if="$store.state.user.userType === 'cook' ||
                                $store.state.user.userType === 'admin'" 
                                to="/items-inventory-records" name="items-inventory">
                                    <Icon type="md-archive" style="color:brown !important"/>
                                    <span class="submenu_text">Inventory Records</span>
                                </MenuItem>
                                <MenuItem v-if="$store.state.user.userType === 'cook' ||
                                $store.state.user.userType === 'admin'" to="/categories" name="categories">
                                    <Icon type="md-apps" />
                                    <span class="submenu_text">Categories</span>
                                </MenuItem>

                                <Submenu name="credit">
                                <template slot="title">
                                    <Icon type="md-card" style="color:#7a4a59 !important" />
                                    <span class="submenu_text" style="color:#7a4a59 !important">Credit</span>
                                </template>
                                <MenuItem
                                    to="/creditor-pos"
                                    name="creditor-pos"
                                    style="color:#7a4a59 !important" 
                                    v-if="$store.state.user.userType === 'admin'
                                    || $store.state.user.userType === 'waiter'"
                                >
                                    <Icon type="ios-card" style="color:#7a4a59 !important" />
                                    <span class="submenu_text"
                                        > Creditor POS</span
                                    >
                                </MenuItem>
                                <MenuItem 
                                    to="/creditor-orders"
                                    name="creditor-orders"
                                    style="color:#7a4a59 !important"
                                    v-if="$store.state.user.userType === 'admin' ||
                                $store.state.user.userType === 'cook'"
                                >
                                    <Icon type="ios-clock" style="color:#7a4a59 !important" />
                                    <span class="submenu_text"
                                        >Orders on Credit</span
                                    >
                                </MenuItem>
                                <MenuItem
                                    to="/creditor-ready-orders"
                                    name="creditor-ready-orders"
                                    style="color:#7a4a59 !important"
                                    v-if="$store.state.user.userType === 'admin' ||
                                $store.state.user.userType === 'waiter'"
                                >
                                    <Icon type="ios-alarm" style="color:#7a4a59 !important" />
                                    <span class="submenu_text"
                                        >Orders pending payment</span
                                    >
                                </MenuItem>
                                <MenuItem
                                    to="/creditors-report"
                                    name="creditors-report"
                                    style="color:#7a4a59 !important"
                                    v-if="$store.state.user.userType === 'admin' ||
                                $store.state.user.userType === 'waiter'"
                                >
                                    <Icon type="md-analytics" style="color:#7a4a59 !important" />
                                    <span class="submenu_text"
                                        > Report</span
                                    >
                                </MenuItem>

                                </Submenu>



                                <MenuItem v-if="$store.state.user.userType === 'admin'"
                                to="/roles" name="roles">
                                    <Icon type="md-contacts" />
                                    <span class="submenu_text">User Roles</span>
                                </MenuItem>
                                <MenuItem to="/reports" v-if="$store.state.user.userType === 'admin'"
                                name="reports">
                                    <Icon type="md-analytics" />
                                    <span class="submenu_text">Reports</span>
                                </MenuItem>
            
                                <MenuItem v-if="$store.state.user.userType === 'admin'" 
                                to="/companies"  name="companies">
                                    <Icon type="ios-grid" />
                                    <span class="submenu_text">Companies</span>
                                </MenuItem>
                                <MenuItem v-if="$store.state.user.userType === 'admin' ||
                                $store.state.user.userType === 'cook'
                                || $store.state.user.userType === 'waiter'"
                                to="/tables" name="tables" >
                                    <Icon type="md-restaurant" style="color:green !important"/>
                                    <span class="submenu_text">Tables</span>
                                </MenuItem>


                            </MenuGroup>
                        </Menu>
                    </div>

                    <div class="_left_sidebar_bottom _1border_color">
                        <Menu :theme="theme3" active-name="1">
                            <MenuItem name="sunny">
                                <Icon
                                    @click="lightSidebarClick"
                                    type="ios-sunny"
                                />
                                <span
                                    @click="lightSidebarClick"
                                    class="submenu_text"
                                    >Light</span
                                >
                            </MenuItem>
                            <MenuItem name="moon">
                                <Icon @click="darkModeClick" type="ios-moon" />
                                <span
                                    @click="darkModeClick"
                                    class="submenu_text"
                                    >Dark</span
                                >
                            </MenuItem>
                            <a
                                style="text-decoration:none;display:inline"
                                href="/logout"
                            >
                                <MenuItem name="logout">
                                    <Icon type="md-log-out" />
                                    <span class="submenu_text">Logout</span>
                                </MenuItem>
                            </a>
                        </Menu>
                        <!-- <p>Change theme</p>
                <RadioGroup v-model="theme3">
                    <Radio label="light"></Radio>
                    <Radio label="dark"></Radio>
            </RadioGroup>-->
                    </div>
                </div>
            </aside>
            <!-- Sidebar -->

            <div
                :class="[
                    sidebar ? '_main_layout _mini_main_layout' : '_main_layout',
                    $route.path != '/login' &&
                    $route.path != '/register' &&
                    $route.path != '/resetPassword' &&
                    $route.path != '/forgetPassword'
                        ? ''
                        : '_login_layout'
                ]"
            >
                <div class="container-fluid">
                <Welcome v-if="$route.path == '/'"/>
                <router-view></router-view>
                </div>
                <Footer
                    v-if="
                        $route.path != '/login' &&
                            $route.path != '/register' &&
                            $route.path != '/resetPassword' &&
                            $route.path != '/forgetPassword' &&
                            $route.path != '/messenger'
                    "
                />
            </div>
        </div>
    </div>
</template>

<script>
import Footer from "./footer.vue";
import Welcome from "./welcome.vue";
export default {
    props: ['user'],
    components: {
        Footer,
        Welcome
    },

    data() {
        return {
            orderNotificationsCount: 0,
            readyOrdersCount: 0,
            userSession: [],
            activeRoute: "index",
            sidebar: false,
            isHovering: false,
            theme3: "light",
            lightSidebar: true,
            darkSidebar: false,
            darkMode: false,
            mobSearchOpen: false,
            mobSidebar: false
        };
    },

    methods: {
        async getRequestedOrdersCount(){
            const getOrdersCount = await this.callApi(
                "get",
                `app/get_requested_orders_count/${this.orderNotificationsCount}`
            );
            if (getOrdersCount.data !== this.orderNotificationsCount) {
                this.orderNotificationsCount = getOrdersCount.data
            }
        },
        async getReadyOrdersCount(){
            const getOrdersCount = await this.callApi(
                "get",
                "app/get_ready_orders_count"
            );
            if (getOrdersCount.status == 200) {
                this.readyOrdersCount = getOrdersCount.data
            }
        },
        async logout() {
            const logout = await this.callApi("get", "/logout");
        },
        lightSidebarClick() {
            this.lightSidebar = true;
            this.darkSidebar = false;
            this.darkMode = false;
        },

        darkModeClick() {
            this.darkSidebar = true;
            this.lightSidebar = false;
            this.darkMode = true;
        }
    },

    created() {
        this.$store.commit('setUpdateUser', this.user)
        console.log("state",this.$store.state.user)
        console.log(this.$route.path)
        this.activeRoute = this.$route.name;
        this.userSession = this.$store.state.user
        //setInterval(this.getRequestedOrdersCount, 2000)
        //setInterval( this.getReadyOrdersCount ,2000)

    }
};
</script>

<style scoped>

._navbar_logo{
    color: orange;
}
.demo-spin-icon-load {
    animation: ani-demo-spin 1s linear infinite;
}
@keyframes ani-demo-spin {
    from {
        transform: rotate(0deg);
    }
    50% {
        transform: rotate(180deg);
    }
    to {
        transform: rotate(360deg);
    }
}
.demo-spin-col {
    height: 200px;
    position: relative;
    /* border: 1px solid #eee; */
}
</style>
