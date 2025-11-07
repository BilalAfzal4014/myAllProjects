import { COMPANIES_LIMIT } from "@/common/constants/constants";
import { mapGetters } from "vuex";

const getCompaniesMixin = {
    data() {
        return {
            companies: [],
            isLogin: !!this.$store.getters.getStoreTokenGetters,
            count: 0,
            isStateHasData: false,
        };
    },
    computed: {
        ...mapGetters({
            companiesList: "getCompaniesList",
        }),
    },
    sockets: {
        connect: function () {
            this.getCompanyFilters();
            this.isStateHasData = !!this.companiesList.length;
        },
        client_get_company_list: function (data) {
            let resultCompanies = JSON.parse(data);
            this.companies[this.count].company = resultCompanies.data.companies;
            if (!this.isStateHasData)
                this.$store.commit("setCompaniesList", this.companies);
            this.count++;
            if (this.count < this.categories.length) {
                this.getCompanyList(this.count);
            } else {
                this.count = 0;
                if (this.isStateHasData) {
                    this.$store.commit("setCompaniesList", this.companies);
                }
            }
        },
    },
    methods: {
        getCompanyFilters: async function () {
            const { data } = await this.oldApi(
                "get",
                this.constants.getUrl("getCategories"),
                true
            );
            this.categories = data;
            await this.getCompanyList(this.count);
        },
        getCompanyList: async function (category) {
            const companyDetails = {};
            companyDetails.company = [];
            companyDetails.category_name = this.categories[category].title;
            companyDetails.title = "See All";
            companyDetails.link = "/category-detail/" + this.categories[category].id;
            this.companies.push(companyDetails);
            this.$socket.emit(
                "server_get_company_list",
                await this.getCompaniesPayload(category)
            );
        },
        getCompaniesPayload: async function (category) {
            let userPosition = await this.constants.askUserLocation();
            return {
                profile_cat_id: this.categories[category].id,
                keyword: "",
                limit: COMPANIES_LIMIT,
                latitude: userPosition?.latitude ? userPosition?.latitude : null,
                longitude: userPosition?.longitude ? userPosition?.longitude : null,
                offset: 0,
            };
        },
    },
};

export default getCompaniesMixin;
