import {Controller} from "@hotwired/stimulus";

 const MAX_ANGLE = 110;

export default class extends Controller {
    static values = {
        maxAngle: {type: Number, defaultValue: MAX_ANGLE},
        offset: {type: Number, defaultValue: 0}
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
        });
    }
}
