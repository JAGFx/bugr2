import {Controller} from "@hotwired/stimulus";

 const MAX_ANGLE = 110;

export default class extends Controller {
    static values = {
        maxAngle: {type: Number, defaultValue: MAX_ANGLE},
        offset: {type: Number, defaultValue: 0}
    }

    calculateTitleOffset(angle) {
        const absAngle = Math.abs(angle);
        const normalizedAngle = Math.min(90, Math.max(0, absAngle));

            const t = (normalizedAngle - 45) / 45;
            return -0.5 - (0.5 * t);
    }

    calculateStackOffset(angle) {
        const normalizedAngle = MAX_ANGLE - angle;

        if (normalizedAngle >= 90) {
            return 0;
        }

        if (normalizedAngle >= 60) {
            const intensity = (normalizedAngle - 60) / 30;
            return -0.2 * intensity;
        }

        if (normalizedAngle < 60) {
            const intensity = (60 - normalizedAngle) / 60;
            const majorSpacing = -.5;
            const stackEffect = -3.3 * intensity * intensity;

            return majorSpacing + stackEffect;
        }

        return 0;
    }

    connect() {
        const maxAngle = this.maxAngleValue;
        const offset = this.offsetValue;

        const menuItems = this.targets.findAll('menu_item');

        if( menuItems.length < 1 ) {
            console.warn("No menu items found for radial menu");
            return;
        }

        const stepDegrees = maxAngle / (menuItems.length - 1);

        menuItems.forEach( (item, index) => {
            const angle = -maxAngle + (index * stepDegrees) + offset;
            const angleCorrection = angle * -1;
            const anglePointer = (90 - angleCorrection) ;

            item.style.setProperty('--angle', `${angle}deg`);
            item.style.setProperty('--angle-correction', `${angleCorrection}deg`);
            item.style.setProperty('--angle-pointer', `${anglePointer}deg`);

            const titleOffset = this.calculateTitleOffset(angleCorrection);
            const stackOffset = this.calculateStackOffset(Math.abs(angle));

            item.style.setProperty('--title-offset', `${titleOffset}rem`);

            const title = item.title ?? '';
            const titleElement = document.createElement('small');
            titleElement.classList.add('title', 'badge');
            titleElement.textContent = title;
            titleElement.style.setProperty('--title-offset',
                stackOffset === 0 ? 'initial' : `${stackOffset}rem`
            );

            item.appendChild(titleElement);
        });
    }
}
