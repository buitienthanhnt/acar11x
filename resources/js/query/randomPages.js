import rApi from "@/network/rApi";
import Urls from "@/network/Urls";

/**
 * get ramdom pages.
 */
const randomPages = async (limit = 6) => {
	const response = await rApi.callRequest({
		url: Urls.pageRandom+ `?limit=${limit}`,
		method: 'GET',
	});
	return response;
}

export default randomPages;