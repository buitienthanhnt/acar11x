<?php

namespace App\Enums;

enum PageEnum: string
{
	case Increment = 'inc';
	case Decrement = 'dic';
	case Heart = 'heart';
	case Like = 'like';
	case Fire = 'fire';
	case Star = 'star';
	case View = 'view';
}
