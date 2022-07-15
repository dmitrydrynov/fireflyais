<template>
    <div class="row">
        <div class="col-md-12">
            <div class="panel panel-default">
                <div class="panel-heading">Edit member</div>
                <div class="panel-body">
                    <form v-on:submit.prevent="handleSave">
                        <div
                            v-if="errors.submit"
                            class="alert alert-danger"
                            role="alert"
                        >
                            {{ errors.submit }}
                        </div>

                        <div
                            class="form-group"
                            v-bind:class="{ 'has-error': !!errors.email }"
                        >
                            <label for="email">Email address</label>
                            <input
                                type="email"
                                class="form-control"
                                name="email"
                                placeholder="Email"
                                :value="staticData.email"
                                @change="clearErrors()"
                                disabled
                            />
                            <p v-show="!!errors.email" class="help-block">
                                {{ errors.email }}
                            </p>
                        </div>

                        <div
                            class="form-group"
                            v-bind:class="{ 'has-error': !!errors.permissions }"
                        >
                            <label>Permissions</label>
                            <p v-show="!!errors.permissions" class="help-block">
                                {{ errors.permissions }}
                            </p>
                            <a-tree
                                v-model="formData.permissions"
                                checkable
                                :selectable="false"
                                :tree-data="permissions"
                            />
                        </div>

                        <a-button type="primary" html-type="submit">
                            Save
                        </a-button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import { Tree, Button } from "ant-design-vue";

export default {
    components: {
        "a-tree": Tree,
        "a-button": Button,
    },
    mounted() {
        this.initialize();
    },
    data: () => {
        return {
            staticData: {
                email: null,
            },
            formData: {
                id: null,
                permissions: [],
            },
            errors: {
                permissions: null,
                submit: null,
            },
            permissions: [],
        };
    },
    methods: {
        initialize() {
            this.staticData.email = email;

            this.formData.id = memberId;
            this.formData.permissions = userPermissions;

            this.permissions = this.preparePermissions(permissions);
        },
        preparePermissions(_permissions, level = 0) {
            return _permissions
                ?.filter((p) => {
                    const _level = p.name.split(".").length - 1;
                    return _level === level;
                })
                .map((p) => {
                    const children = this.preparePermissions(
                        _permissions.filter((ch) => {
                            return (
                                ch.name.startsWith(p.name + ".") &&
                                p.name !== ch.name
                            );
                        }),
                        level + 1
                    );

                    return {
                        title: p.name,
                        key: p.name,
                        children,
                    };
                });
        },
        async handleSave() {
            if (!this.validateFormData()) return;

            try {
                const response = await axios.post("/api/v1/members/update", {
                    ...this.formData,
                });

                if (response.data.success) {
                    window.location.href = previousUrl + "?message=updated";
                }
            } catch (error) {
                if (error.response.data.code === "23505") {
                    this.errors.submit = "User with this email already exist.";
                } else {
                    this.errors.submit = "Something wrong. Try again later.";
                }
            }
        },
        validateFormData() {
            if (this.formData.id && this.formData.permissions.length > 0) {
                return true;
            }

            this.clearErrors();

            if (!this.formData.permissions.length) {
                this.errors.permissions =
                    "At least one option must be selected";
            }

            return false;
        },
        clearErrors() {
            this.errors = {
                permissions: null,
                submit: null,
            };
        },
    },
};
</script>
