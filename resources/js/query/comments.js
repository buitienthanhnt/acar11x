import rApi from "@/network/rApi"
import Urls from "@/network/Urls";

const getCommentList = async (targetId, parent_id, page = 1) => {
	const response = await rApi.callRequest({
		url: Urls.getComments,
		method: 'GET',
		params: {
			page: page,
			targetId: targetId,
			type: null,
			parent_id: parent_id,
		}
	});

	return response;
}

const getTopComment = async ()=>{
	return await rApi.callRequest({
		url: Urls.comment.topPage,
		method: 'GET',
	})
}
export { getCommentList, getTopComment }
