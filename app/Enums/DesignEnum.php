<?php

namespace App\Enums;

enum DesignEnum : string {
	case Banner = 'banner';
	case TopPage = 'topPage';
    case TopComment = 'topComment';
    case PageRandom = 'pageRandom';
	case TimeLine = 'timeLine';
	case CenterCategory = 'centerCategory';
    case LatestPage = 'latestPage';
    case Videos = 'videos';
	case Suggest = 'suggest';
	case GridPage = 'gridPage';
    case Approved = 'listVertical';
    case ListHorizon = 'listHorizon';
	case Chart = 'chart';
}