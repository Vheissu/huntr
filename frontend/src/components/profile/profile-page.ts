import { IAuth } from "../../services/auth";
import { ILoader } from "../../services/loader";

export class ProfilePage {
    private user = null;

    constructor(@IAuth readonly auth: IAuth, @ILoader readonly loader: ILoader) {

    }

    created() {
        this.loader.show();
    }

    async binding() {
        this.user = await this.auth.user();

        this.loader.hide();
    }

}