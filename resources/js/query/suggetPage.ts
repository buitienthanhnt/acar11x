
import rApi from '@/network/rApi';
import Urls from '@/network/Urls';
import {vsprintf} from 'sprintf-js';

const suggetPage = async (pageId)=>{
	return await rApi.callRequest({
		method: "GET",
		url: vsprintf(Urls.pages.pageSugget, [pageId]),
	});
}

export {suggetPage};