import rApi from "@/network/rApi";
import Urls from "@/network/Urls";

const getTopPages = async () => {
	return await rApi.callRequest({
		url: Urls.pages.topPage,
		method: "GET",
	});
}

export { getTopPages };