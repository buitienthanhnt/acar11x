import rApi from "@/network/rApi";
import Urls from "@/network/Urls";

const getCenterCategory = async ()=>{
	return await rApi.callRequest({
		method: "GET",
		url: Urls.category.centerCategory
	});
}

export {getCenterCategory};