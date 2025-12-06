import React, { FunctionComponent } from "react";
import { HomeItem as HomeItemType } from "../types/Home.d";
import { Link } from "@inertiajs/react";
import { sprintf } from "sprintf-js";
import Urls from "../netWork/Urls";

const HomeItem = ({ home }: { home: HomeItemType }) => {
	return (
		<Link className="bg-green-200 shadow-md p-2 rounded-md" href={sprintf(Urls.homeDetail, [home.id])}>
			<h4 className="text-2xl text-blue-700 font-bold">{home.name}</h4>
			<p className="text-right text-xl font-semibold">{home.description}</p>
			<p className="italic text-black">{home.district}</p>
		</Link>
	)
}

type Props = {
	homes: HomeItemType[],
}

const HomeList: FunctionComponent<Props> = (params) => {

	if (!params.homes) {
		return null;
	}

	return (
		<div className="p-4 gap-2">
			<h3 className="text-lg font-semibold text-purple-300">List of home register:</h3>
			<div className="p-4 grid grid-cols-3 gap-3">
				{params.homes.map(home => <HomeItem home={home} key={home.id.toString()}></HomeItem>)}
			</div>
		</div>
	)
}

export default HomeList;