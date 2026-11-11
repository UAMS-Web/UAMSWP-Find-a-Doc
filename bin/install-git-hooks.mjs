// Point git at the committed hooks in .githooks. Run by `npm install` (the `prepare` script).
//
// .git/hooks is not tracked, so core.hooksPath is the only way a committed hook takes effect in
// every clone. Outside a git checkout (a downloaded archive, a container build) there is nothing
// to configure, and that is reported rather than failing the install.
import { execFileSync } from 'node:child_process';

try {
    execFileSync('git', ['config', 'core.hooksPath', '.githooks'], { stdio: 'ignore' });
    console.log('git hooks: core.hooksPath set to .githooks');
} catch {
    console.log('git hooks: not installed (no git checkout here, or git is not on the PATH)');
}
