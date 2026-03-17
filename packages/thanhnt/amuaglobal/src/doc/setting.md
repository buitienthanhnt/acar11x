# setting tsx 2 space for vscode
To control spaces for JSON files in VS Code, you should modify specific settings related to indentation within your settings.json file. These settings can be applied globally to all files or specifically for JSON documents. 
Stack Overflow
Stack Overflow
 +4
Modifying settings.json
Open settings.json:
# Open the Command Palette with Ctrl+Shift+P (Windows/Linux) or Cmd+Shift+P (Mac).
# Type Preferences: Open User Settings (JSON) and select it.
Alternatively, you can open the standard settings UI (Ctrl+,) and click the {} icon in the top right.
Add/Modify Settings:
Add the following lines to your settings.json file to configure indentation using spaces:
# ====================>
json
	{
		// Ensures tabs are converted to spaces
		"editor.insertSpaces": true,
		// Sets the default number of spaces per indentation level (e.g., 2 or 4)
		"editor.tabSize": 4,
		// Optional: Prevents VS Code from automatically detecting indentation from file content
		"editor.detectIndentation": false
	}
 # <====================
keracudmore.dev
keracudmore.dev
 +4
Language-Specific JSON Settings
You can override the global settings specifically for JSON files (including settings.json, tasks.json, etc., which often use the jsonc mode): 
keracudmore.dev
keracudmore.dev
 +4
 # =====================>
json
{
  // Global settings above this...

  // Language-specific settings for JSON files
  "[json]": {
    "editor.tabSize": 2, // Use 2 spaces for JSON files
    "editor.insertSpaces": true
  },
  "[jsonc]": {
    "editor.tabSize": 2, // Use 2 spaces for JSON with comments files
    "editor.insertSpaces": true
  }
}
# <=====================


# ===============demo=====================
{
	"terminal.integrated.tabFocusMode": true,
	"terminal.integrated.sendKeybindingsToShell": true,
	"editor.insertSpaces": false,
	"editor.tabSize": 2,
	"editor.detectIndentation": false,
	"editor.tabFocusMode": true,
	"editor.tabCompletion": "on",
	"git.confirmSync": false,
	"terminal.integrated.tabs.focusMode": "singleClick",
	"workbench.editor.highlightModifiedTabs": true,
	"emmet.triggerExpansionOnTab": true,
	"[php]": {
		"editor.defaultFormatter": "bmewburn.vscode-intelephense-client"
	},
	"workbench.editor.editorActionsLocation": "titleBar",
	"[json]": {
		"editor.defaultFormatter": "vscode.json-language-features"
	},
	"[javascriptreact]": {
		"editor.defaultFormatter": "rvest.vs-code-prettier-eslint"
	},
	"[javascript]": {
		"editor.defaultFormatter": "rvest.vs-code-prettier-eslint"
	},
	"[html]": {
		"editor.defaultFormatter": "vscode.html-language-features"
	},
	"explorer.fileNesting.patterns": {
		"*.ts": "${capture}.js",
		"*.js": "${capture}.js.map, ${capture}.min.js, ${capture}.d.ts",
		"*.jsx": "${capture}.js",
		"*.tsx": "${capture}.ts",
		"tsconfig.json": "tsconfig.*.json",
		"package.json": "package-lock.json, yarn.lock, pnpm-lock.yaml, bun.lockb, bun.lock",
		"*.sqlite": "${capture}.${extname}-*",
		"*.db": "${capture}.${extname}-*",
		"*.sqlite3": "${capture}.${extname}-*",
		"*.db3": "${capture}.${extname}-*",
		"*.sdb": "${capture}.${extname}-*",
		"*.s3db": "${capture}.${extname}-*"
	},
	"[jsonc]": {
		"editor.defaultFormatter": "vscode.json-language-features"
	},
	"[typescript]": {
		"editor.defaultFormatter": "vscode.typescript-language-features",
		"editor.tabSize": 2, // Use 2 spaces for JSON files
		"editor.insertSpaces": true
	},
	"[typescriptreact]": {
		"editor.defaultFormatter": "rvest.vs-code-prettier-eslint",
		"editor.tabSize": 2, // Use 2 spaces for JSON files
    "editor.insertSpaces": true
	},
	"[css]": {
		"editor.defaultFormatter": "vscode.css-language-features"
	}
}
# ==============================================================================
# fix when press tab key not work: Ctrl + Shift + p -> user setting -> remove: "editor.tabFocusMode": true,
