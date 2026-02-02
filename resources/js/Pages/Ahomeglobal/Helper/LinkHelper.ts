import { sprintf } from "sprintf-js";
import Urls from "../netWork/Urls";
import { HomeItemType } from "../types/Home";

export const linkHomeDetail = (home: HomeItemType) => {
	return sprintf(Urls.homeDetail, [home.id]);
}